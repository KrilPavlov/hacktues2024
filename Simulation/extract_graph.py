import cv2
import pytesseract
from pytesseract import Output
import numpy as np
import networkx as nx

# Load the image
img = cv2.imread('data/nodes_only.png')

# Convert the image to grayscale
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

# Use thresholding to isolate the nodes - this value may need adjustment
_, thresh = cv2.threshold(gray, 200, 255, cv2.THRESH_BINARY_INV)

# Find contours which will be the nodes
contours, _ = cv2.findContours(thresh, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)

# This will hold the centroid positions of the nodes
nodes_positions = {}

# Iterate over contours and compute the centroids
for i,contour in enumerate(contours):
    M = cv2.moments(contour)
    # Skip small contours that may be noise or not actual nodes
    if M["m00"] != 0 and cv2.contourArea(contour) > 10:
        cX = int(M["m10"] / M["m00"])
        cY = int(M["m01"] / M["m00"])
        # Draw the contour and center of the shape on the image
        cv2.drawContours(img, [contour], -1, (0, 255, 0), 2)
        cv2.circle(img, (cX, cY), 7, (255, 255, 255), -1)

        # Use pytesseract to do OCR on the centroids
        # We need to expand the area for OCR to work better
        x, y, w, h = cv2.boundingRect(contour)
        roi = thresh[y - 10:y + h + 10, x - 10:x + w + 10]
        # text = pytesseract.image_to_string(roi, config='--psm 8 -c tessedit_char_whitelist=0123456789')
        text = str(i)
        node_id = ''.join(filter(str.isdigit, text))

        if node_id:
            nodes_positions[int(node_id)] = (cX, cY)

# Since the OCR part can be tricky and might not work perfectly in this automated environment,
# normally we would check the outputs and adjust parameters or preprocessing steps as needed.

# We create a graph using NetworkX and add nodes with their positions
G = nx.Graph()
for node_id, (x, y) in nodes_positions.items():
    G.add_node(node_id, pos=(x, y))

# Return the positions and a visualization of the nodes on the map
positions = {node_id: data['pos'] for node_id, data in G.nodes(data=True)}
print(positions)
node_positions = positions







from sklearn.cluster import DBSCAN
import numpy as np

positions = np.array(list(node_positions.values()))

# Run DBSCAN clustering algorithm to group points that are close to each other
# eps is the maximum distance between two samples for them to be considered as in the same neighborhood
# min_samples is the number of samples in a neighborhood for a point to be considered as a core point
db = DBSCAN(eps=10, min_samples=1).fit(positions)

# Labels will give you the cluster id for each point
labels = db.labels_

# Create a new dictionary with one point per cluster
filtered_node_positions = {}
for label, position in zip(labels, positions):
    if label not in filtered_node_positions:
        filtered_node_positions[label] = position


node_positions = filtered_node_positions
del(node_positions[0])

print(node_positions)












new_image = cv2.imread('data/map-pirin.jpg')

# Overlay the node positions on the new image
for node_id, (x, y) in node_positions.items():
    cv2.circle(new_image, (x, y), 5, (0, 255, 0), -2)
    cv2.putText(new_image, str(node_id), (x - 10, y - 10),
                cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 2)

cv2.imwrite('data/overlayed_image.png', new_image)
cv2.imshow('Overlayed Image', new_image)
cv2.waitKey(0)
cv2.destroyAllWindows()




import csv
csv_file = "node_positions.csv"

# Open the file and write the dictionary content
with open(csv_file, mode='w', newline='') as file:
    writer = csv.writer(file)
    
    # Write the header
    writer.writerow(['Node ID', 'X Position', 'Y Position'])
    
    # Write the node positions
    for node_id, position in node_positions.items():
        writer.writerow([node_id, position[0], position[1]])

print(f"node_positions have been exported to {csv_file}")
