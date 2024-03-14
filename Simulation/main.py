import pandas as pd
import numpy as np
import networkx as nx
import cv2
import matplotlib.pyplot as plt
import random
import time
from TouristSim import Tourist

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


# Prepare position mapping
pos = {node: (x, y) for node, (x, y) in enumerate(node_positions.values())}
# Create a named window
cv2.namedWindow('Graph on Map', cv2.WINDOW_NORMAL)  # Create a resizable window
cv2.resizeWindow('Graph on Map', 600, 600)  # Set the window size




num_tourists = 5
start_nodes = [24,23,21,16,10]
tourists = []
for i in range(num_tourists):
    start_id = random.choice(start_nodes)
    tourists.append(Tourist(start_id, adjacency_matrix, node_positions))



# Display loop
while True:
    frame = img.copy()  # Work on a copy of the image
    draw_graph_on_image(frame, G, pos)


    for tourist in tourists:
        tourist.updatePosition(5)
        if tourist.progress >=1:
            tourist.newRoute()
        draw_tourist_on_image(frame, tourist.cur_pos, "data/tourist.png", scale = 0.1)
        

    cv2.imshow('Graph on Map', frame)
    time.sleep(1)
    # Break loop with ESC key
    if cv2.waitKey(1) & 0xFF == 27:
        break

cv2.destroyAllWindows()
