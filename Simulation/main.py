import pandas as pd
import numpy as np
import networkx as nx
import cv2
import matplotlib.pyplot as plt
import random
import time
import string
import threading
from collections import deque
from TouristSim import Tourist
from SensorSim import Sensor

LOAD_SENSORS = True

# Load node positions and adjacency matrix
node_positions_df = pd.read_csv('data/node_positions.csv')
node_positions = node_positions_df.set_index('Node ID').T.to_dict('list')

adjacency_matrix_df = pd.read_csv('data/node_adj.csv', sep = ',', header = None)
adjacency_matrix = adjacency_matrix_df.values

# Create the graph from the adjacency matrix
G = nx.from_numpy_array(adjacency_matrix)
G = nx.Graph(G)  # This removes parallel edges and self-loops

# Load image using OpenCV
image_path = 'data/map-pirin.jpg'  # Replace with the path to your image
img = cv2.imread(image_path)

# Function to draw the graph on the image
def draw_graph_on_image(image, graph, positions):
    # Draw edges
    for edge in graph.edges:
        pt1 = (int(positions[edge[0]][0]), int(positions[edge[0]][1]))
        pt2 = (int(positions[edge[1]][0]), int(positions[edge[1]][1]))
        cv2.line(image, pt1, pt2, (255, 0, 0), 5)  # Blue edges

    # Draw nodes
    for node, pos in positions.items():
        cv2.circle(image, (int(pos[0]), int(pos[1])), 10, (0, 0, 255), -1)  # Red nodes

def draw_tourist_on_image(image, position, icon_path, scale=1.0):
    """
    Draws a tourist icon on the image at the specified position.

    Parameters:
    - image: The map image as a numpy array.
    - position: A tuple (x, y) indicating where to draw the tourist on the map.
    - icon_path: Path to the tourist icon image file.
    - scale: Scaling factor for resizing the tourist icon. Default is 1.0 (no scaling).
    """
    position = (position[0] - 50, position[1] - 50)
    # Load the tourist icon
    icon = cv2.imread(icon_path, cv2.IMREAD_UNCHANGED)
    
    # Resize the icon based on the scale parameter
    width = int(icon.shape[1] * scale)
    height = int(icon.shape[0] * scale)
    resized_icon = cv2.resize(icon, (width, height))
    
    # Create a mask for the icon and its inverse mask
    mask = resized_icon[:, :, 3]
    mask_inv = cv2.bitwise_not(mask)
    resized_icon = resized_icon[:, :, :3]  # Use only the 3 color channels
    
    # Cut out the area of the tourist icon from the background
    y1, y2 = position[1], position[1] + height
    x1, x2 = position[0], position[0] + width
    roi = image[y1:y2, x1:x2]
    
    # Mask out the tourist icon area on the ROI
    roi_bg = cv2.bitwise_and(roi, roi, mask=mask_inv)
    roi_fg = cv2.bitwise_and(resized_icon, resized_icon, mask=mask)
    
    # Combine the background and the icon
    dst = cv2.add(roi_bg, roi_fg)
    
    # Put back in the original image
    image[y1:y2, x1:x2] = dst

def overlay_rectangle_on_sensor(image, sensor_pos, sensor_id, rectangle_size=(25, 25), color=(255, 0, 255), thickness=-1, font_scale=0.5):
    """
    Overlays a rectangle on the given sensor position on the image and prints the sensor's ID inside it.

    Parameters:
    - image: The image as a numpy array on which to overlay the rectangle.
    - sensor_pos: The position (x, y) of the sensor on the image.
    - sensor_id: The ID of the sensor, which will be printed inside the rectangle.
    - rectangle_size: A tuple (width, height) defining the size of the rectangle. Default is (25, 25).
    - color: A tuple (B, G, R) defining the color of the rectangle. Default is magenta (255, 0, 255).
    - thickness: The thickness of the rectangle's border. Default is 2. If it is negative, it fills the rectangle.
    - font_scale: The scale of the font used to print the sensor ID. Default is 0.5.
    """
    x, y = sensor_pos
    top_left = (int(x - rectangle_size[0] / 2), int(y - rectangle_size[1] / 2))
    bottom_right = (int(x + rectangle_size[0] / 2), int(y + rectangle_size[1] / 2))
    
    # Draw the rectangle
    cv2.rectangle(image, top_left, bottom_right, color, thickness)

    # Calculate the text size to center it
    text = str(sensor_id)
    text_size = cv2.getTextSize(text, cv2.FONT_HERSHEY_SIMPLEX, font_scale, 2)[0]
    text_x = top_left[0] + (rectangle_size[0] - text_size[0]) // 2
    text_y = top_left[1] + (rectangle_size[1] + text_size[1]) // 2
    
    # Overlay the text on the image
    cv2.putText(image, text, (text_x, text_y), cv2.FONT_HERSHEY_SIMPLEX, font_scale, (255, 255, 255), 2)


def overlay_grid_on_image(image, grid_size=(6, 6), color=(0, 255, 0), thickness=1, font_scale=0.5):
    """
    Overlays a grid on the image and numbers each grid square at the bottom right corner.

    Parameters:
    - image: The image as a numpy array on which to overlay the grid.
    - grid_size: A tuple (rows, cols) defining the grid size. Default is 6x6.
    - color: A tuple (B, G, R) defining the color of the grid lines. Default is green (0, 255, 0).
    - thickness: The thickness of the grid lines.
    - font_scale: The scale of the font used to number the grid squares.
    """
    img_height, img_width = image.shape[:2]
    row_height = img_height // grid_size[0]
    col_width = img_width // grid_size[1]

    # Draw the grid lines
    for i in range(1, grid_size[0]):
        cv2.line(image, (0, i * row_height), (img_width, i * row_height), color, thickness)
    for j in range(1, grid_size[1]):
        cv2.line(image, (j * col_width, 0), (j * col_width, img_height), color, thickness)

    # Number each grid square at the bottom right corner
    for i in range(grid_size[0]):
        for j in range(grid_size[1]):
            square_number = i * grid_size[1] + j
            bottom_right_corner = (j * col_width + col_width, i * row_height + row_height)
            cv2.putText(image, str(square_number), (bottom_right_corner[0] - 20, bottom_right_corner[1] - 10),
                        cv2.FONT_HERSHEY_SIMPLEX, font_scale, (0, 0, 0), 2)




def listen_for_commands(q):
    while True:
        command = input()  # Wait for input from the terminal
        q.append(command)  # Add the command to the queue

def simulation(q):
    global deltaTime, loopDelay
    # Prepare position mapping
    pos = {node: (x, y) for node, (x, y) in enumerate(node_positions.values())}
    # Create a named window
    cv2.namedWindow('Simulation', cv2.WINDOW_NORMAL)  # Create a resizable window
    cv2.resizeWindow('Simulation', 800, 800)  # Set the window size



    #INITIALIZE TOURISTS
    num_tourists = 10
    start_nodes = [24,23,21,16,10]
    tourists = []
    for i in range(num_tourists):
        start_id = random.choice(start_nodes)
        tourists.append(Tourist(start_id, adjacency_matrix, node_positions))



    #INITIALIZE SENSORS
    sim_id = ''.join(random.choices(string.ascii_letters + string.digits, k=32))
    if LOAD_SENSORS!=True:
        num_sensors = 25
        sensors = []
        used_segments = set()  # Set to keep track of segments already with a sensor
        sensor_id=0

        while len(sensors) < num_sensors:
            start_node = random.randint(0, len(adjacency_matrix) - 1)
            adjacent_nodes = [index for index, value in enumerate(adjacency_matrix[start_node]) if value != 0]
            
            if not adjacent_nodes:
                continue  # Skip to the next iteration if there are no adjacent nodes
            
            # Filter adjacent nodes to exclude those leading to segments already used
            valid_adjacent_nodes = [node for node in adjacent_nodes if (start_node, node) not in used_segments and (node, start_node) not in used_segments]
            
            if not valid_adjacent_nodes:
                continue  # Skip to the next iteration if there are no valid segments left
            
            end_node = random.choice(valid_adjacent_nodes)
            sensors.append(Sensor(start_node, end_node, node_positions, adjacency_matrix, sim_id, sensor_id))
            used_segments.add((start_node, end_node))
            used_segments.add((end_node, start_node))  # Add this if your graph is undirected to avoid reverse duplicates
            sensor_id+=1

        sensor_data = [{
        "sensor_id": sensor.sensor_id,
        "start_node": sensor.start_node,
        "end_node": sensor.end_node,
        "position_x": sensor.pos[0],
        "position_y": sensor.pos[1]
        } for sensor in sensors]
        df_sensors = pd.DataFrame(sensor_data)
        csv_file_path = 'data/sensors.csv'
        df_sensors.to_csv(csv_file_path, index=False)
    else:
        print("Loading sensor locations")
        # Load the sensors from the CSV file
        csv_file_path = 'data/sensors.csv'
        df_sensors = pd.read_csv(csv_file_path)

        # If you need to recreate Sensor objects from the loaded data
        sensors = []
        for _, row in df_sensors.iterrows():
            sensor_id = int(row['sensor_id'])
            start_node = int(row['start_node'])
            end_node = int(row['end_node'])
            position = (row['position_x'], row['position_y'])
            # Assuming Sensor class initialization looks something like this
            # Adjust the arguments based on the actual Sensor class definition
            sensor = Sensor(start_node, end_node, node_positions, adjacency_matrix, sim_id, sensor_id, position=position)
            sensors.append(sensor)



    # Simulation loop
    paused = False
    deltaTime = 10
    timestamp = 0
    loopDelay= 2
    while True:

        if q:  # Check if the queue has any commands
            command = q.popleft()  # Get the next command
            if command.startswith("deltaTime = "):
                deltaTime = int(command.split("=")[1].strip())
            elif command.startswith("loopDelay = "):
                loopDelay = int(command.split("=")[1].strip())
            elif command == "pause":
                paused = not paused


        if paused:
            continue  # Skip the rest of the loop if paused

        frame = img.copy()  # Work on a copy of the image

        overlay_grid_on_image(frame, grid_size=(6, 6), color=(0, 255, 0), thickness=3, font_scale=0.5)
        draw_graph_on_image(frame, G, pos)

        for sensor in sensors:
            overlay_rectangle_on_sensor(frame, sensor.pos, sensor.sensor_id)
            sensor.detect(tourists, timestamp = timestamp)

        for tourist in tourists:
            tourist.updatePosition(deltaTime)
            if tourist.progress >=1:
                tourist.newRoute()
            draw_tourist_on_image(frame, tourist.cur_pos, "data/tourist.png", scale = 0.1)
        
        timestamp +=deltaTime

        cv2.imshow('Simulation', frame)
        time.sleep(loopDelay)
        # Break loop with ESC key
        if cv2.waitKey(1) & 0xFF == 27:
            break

    cv2.destroyAllWindows()



if __name__ =="__main__":
    q = deque()
    sim_thread = threading.Thread(target=simulation, args=(q,))
    cmd_thread = threading.Thread(target=listen_for_commands, args=(q,))
    sim_thread.start()
    cmd_thread.start()
