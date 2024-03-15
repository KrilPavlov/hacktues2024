import cv2
import numpy as np
import networkx as nx
import pandas as pd


node_positions_df = pd.read_csv('data/node_positions.csv')
node_positions = node_positions_df.set_index('Node ID').T.to_dict('list')

adjacency_matrix_df = pd.read_csv('data/node_adj.csv', sep = ',', header = None)
adjacency_matrix = adjacency_matrix_df.values

# Create the graph from the adjacency matrix
G = nx.from_numpy_array(adjacency_matrix)
G = nx.Graph(G)  # This removes parallel edges and self-loops


# Function to convert geographical coordinates to pixel coordinates
def geo_to_pixel(lat, long, nw_geo, se_geo, image_width, image_height):
    lat_ratio = (nw_geo[0] - lat) / (nw_geo[0] - se_geo[0])
    long_ratio = (long - nw_geo[1]) / (se_geo[1] - nw_geo[1])
    x = int(long_ratio * image_width)
    y = int(lat_ratio * image_height)
    return (x, y)

# Load the image
img = cv2.imread('data/map-pirin.jpg')

# Image dimensions
image_width = img.shape[1]
image_height = img.shape[0]

# Geographic coordinates of the image corners
nw_geo = (41.9, 23.3) # North West
se_geo = (41.6, 23.6) # South East

# Number of squares per side
num_squares_side = 6

# Calculate step sizes for latitude and longitude (assuming they are the same as before)
lat_step = (nw_geo[0] - se_geo[0]) / num_squares_side
long_step = (se_geo[1] - nw_geo[1]) / num_squares_side

# Drawing the grid and numbering the squares
square_number = 1
for row in range(num_squares_side):
    for col in range(num_squares_side):
        top_left_geo = (nw_geo[0] - row * lat_step, nw_geo[1] + col * long_step)
        bottom_right_geo = (nw_geo[0] - (row + 1) * lat_step, nw_geo[1] + (col + 1) * long_step)
        
        top_left_pixel = geo_to_pixel(*top_left_geo, nw_geo, se_geo, image_width, image_height)
        bottom_right_pixel = geo_to_pixel(*bottom_right_geo, nw_geo, se_geo, image_width, image_height)
        
        # Draw rectangle
        cv2.rectangle(img, top_left_pixel, bottom_right_pixel, (255, 0, 0), 2)
        
        # Put square number
        font = cv2.FONT_HERSHEY_SIMPLEX
        bottom_left_corner_of_text = top_left_pixel[0] + 5, bottom_right_pixel[1] - 5
        cv2.putText(img, str(square_number), bottom_left_corner_of_text, font, 0.5, (255, 0, 0), 2)
        
        square_number += 1

# Display the result
cv2.imshow('Grid Overlay', img)
cv2.waitKey(0)
cv2.destroyAllWindows()

# Optionally, save the result
cv2.imwrite('image_with_grid.jpg', img)
