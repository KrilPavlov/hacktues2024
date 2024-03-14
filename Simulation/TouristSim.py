import numpy as np
import random
import math

MAX_SPEED = 7
MIN_SPEED = 1.5

class Tourist():

    def newRoute(self):
        self.start_node = self.end_node

        edges = self.adj[self.start_node]
        # Find indices of non-zero elements, which represent node IDs to which the given node is connected
        adjacent_nodes = np.where(edges > 0)[0]
        self.end_node = random.choice(adjacent_nodes)

        self.speed = random.uniform(MIN_SPEED, MAX_SPEED)
        self.progress = 0
        self.start_pos = self.node_pos[self.start_node]
        self.cur_pos = self.node_pos[self.start_node]
        self.end_pos = self.node_pos[self.end_pos]

    def updatePosition(self, deltaTime):

        x1, y1 = self.start_pos
        x2, y2 = self.cur_pos
        x3, y3 = self.end_pos
    
        # Calculate the distance between P1 and P2
        D = math.sqrt((x2 - x1)**2 + (y2 - y1)**2)
        
        # Calculate total travel time from P1 to P2
        T = D / self.speed
    
        # Calculate current position at time t
        x = x2 + (x3 - x2) * (deltaTime / T)
        y = y2 + (y3 - y2) * (deltaTime / T)

        self.cur_pos = (x, y)

        Dtot = math.sqrt((x3 - x1)**2 + (y3 - y1)**2)
        self.progress = D/Dtot

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

