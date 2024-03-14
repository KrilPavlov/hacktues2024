#%%

import pandas as pd
import numpy as np
import networkx as nx
import matplotlib.pyplot as plt
from PIL import Image

node_positions_df = pd.read_csv('data/node_positions.csv')
node_positions = node_positions_df.set_index('Node ID').T.to_dict('list')


adjacency_matrix_df = pd.read_csv('data/node_adj.csv', sep = ',', header = None)
adjacency_matrix = adjacency_matrix_df.values

# Create the graph from the adjacency matrix
G = nx.from_numpy_array(adjacency_matrix)

# Remove duplicated edges
G = nx.Graph(G)  # This removes parallel edges and self-loops

# Load image
image_path = 'data/map-pirin.jpg'  # Replace with the path to your image
img = Image.open(image_path)

# Draw the graph on the image
pos = {node: (x, y) for node, (x, y) in enumerate(node_positions.values())}
plt.figure(figsize=(8, 8))
plt.imshow(img)

# We need to create a position mapping for the nodes in the graph
nx.draw_networkx(G, pos=pos, node_color='red', node_size=50, edge_color='blue', with_labels=True)

# Save the graph on the image
plt.savefig('graph_on_image.png')
plt.show()

# %%
