import numpy as np
import random
import math
import requests
from decimal import Decimal

class Sensor():


    def post(self, dir, speed, timestamp):
        url = 'http://192.168.43.240:8000/sim'
        data = {'sim_id':self.sim_id,'sensorID': self.sensor_id,'DetectedAT':timestamp,'Direction':dir, 'speed':round(speed, 2)}
        print(data)
        response = requests.post(url, data=data)
        print(response)
    pass

    def detect(self, tourists, timestamp, range = 20):

        for tourist in tourists:
            if (tourist.start_node == self.start_node and tourist.end_node == self.end_node) or (tourist.start_node == self.end_node and tourist.end_node == self.start_node):
                distance = math.sqrt((self.pos[0] - tourist.cur_pos[0]) ** 2 + (self.pos[1] - tourist.cur_pos[1]) ** 2)
                if distance <= range:
                    dir = (tourist.start_node==self.start_node)
                    speed = tourist.speed
                    if tourist.detected == False:
                        self.post(dir,speed,timestamp)
                    tourist.detected = True



    def __init__ (self, start_node, end_node, node_pos, adj, sim_id, sensor_id, position = None):

        if position == None:
            self.start_node = start_node
            self.end_node = end_node
            self.node_pos = node_pos
            self.adj = adj
            self.sim_id = sim_id
            self.sensor_id = sensor_id

            start_pos = self.node_pos[self.start_node]
            end_pos = self.node_pos[self.end_node]
            start_x, start_y = start_pos
            end_x, end_y = end_pos
            self.t = random.random()
            self.pos = (start_x + (end_x - start_x) * self.t, start_y + (end_y - start_y) * self.t)

        else:
            self.start_node = start_node
            self.end_node = end_node
            self.node_pos = node_pos
            self.adj = adj
            self.sim_id = sim_id
            self.sensor_id = sensor_id
            self.t = random.random()
            self.pos = position

