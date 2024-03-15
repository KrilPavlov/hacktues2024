import numpy as np
import random
import math

MAX_SPEED = 7
MIN_SPEED = 1.5

class Tourist():

    def newRoute(self):
        old_start_node = self.start_node
        self.start_node = self.end_node

        edges = self.adj[self.start_node]
        # Find indices of non-zero elements, which represent node IDs to which the given node is connected
        adjacent_nodes = np.where(edges > 0)[0]
        if len(adjacent_nodes) > 1:
            filtered_nodes = [node for node in adjacent_nodes if node != old_start_node]
            self.end_node = random.choice(filtered_nodes)
        else:
            self.end_node = random.choice(adjacent_nodes)



        self.speed = random.uniform(MIN_SPEED, MAX_SPEED)
        self.progress = 0
        self.start_pos = self.node_pos[self.start_node]
        self.cur_pos = self.node_pos[self.start_node]
        self.end_pos = self.node_pos[self.end_node]
        
        self.detected = False

    def updatePosition(self, deltaTime):
        # Extract start, current, and end positions
        xs, ys = self.start_pos
        xc, yc = self.cur_pos
        xe, ye = self.end_pos
        
        # Calculate the total distance from start to end
        total_distance = ((xe - xs) ** 2 + (ye - ys) ** 2) ** 0.5
        
        # Determine direction from start to end
        direction_x = xe - xs
        direction_y = ye - ys
        
        # Normalize the direction
        if total_distance > 0:
            direction_x /= total_distance
            direction_y /= total_distance
        
        # Calculate the distance to move based on speed and deltaTime
        distance_to_move = self.speed * deltaTime
        
        # Update current position based on the direction and distance to move
        xc += direction_x * distance_to_move
        yc += direction_y * distance_to_move
        self.cur_pos = (int(xc), int(yc))
        
        # Update progress
        # Calculate the distance from the start position to the current position
        distance_covered = ((xc - xs) ** 2 + (yc - ys) ** 2) ** 0.5
        
        # Calculate progress as a fraction of total distance
        self.progress = distance_covered / total_distance if total_distance > 0 else 1
        
        # Ensure progress does not exceed 1
        self.progress = min(self.progress, 1)


    def __init__(self, start_node, adj, node_pos):
        self.start_node = start_node
        self.adj = adj
        self.node_pos = node_pos

        edges = adj[start_node]
        # Find indices of non-zero elements, which represent node IDs to which the given node is connected
        adjacent_nodes = np.where(edges > 0)[0]
        self.end_node = random.choice(adjacent_nodes)

        self.speed = random.uniform(MIN_SPEED, MAX_SPEED)
        self.progress = 0
        self.start_pos = self.node_pos[self.start_node]
        self.cur_pos = self.node_pos[self.start_node]
        self.end_pos = self.node_pos[self.end_node]

        self.detected = False

