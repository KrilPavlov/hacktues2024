import pandas as pd
import random

# Load the data from CSV files
sensors_df = pd.read_csv('data/sensors.csv')
node_positions_df = pd.read_csv('data/node_positions_converted.csv')

# Convert node_positions_df to a dictionary for faster lookups
node_pos_dict = node_positions_df.set_index('Node ID')[['world_lat', 'world_long']].to_dict('index')

# Initialize an empty list to store the converted sensor data
converted_sensors_data = []

# Iterate over each sensor record
for _, sensor in sensors_df.iterrows():
    start_node = sensor['start_node']
    end_node = sensor['end_node']
    
    # Retrieve the world positions for the start and end nodes
    start_pos = node_pos_dict[start_node]
    end_pos = node_pos_dict[end_node]
    
    # Extract lat and long for both start and end positions
    start_lat, start_long = start_pos['world_lat'], start_pos['world_long']
    end_lat, end_long = end_pos['world_lat'], end_pos['world_long']
    
    # Calculate the real world position of the sensor
    t = random.random()
    sensor_lat = start_lat + (end_lat - start_lat) * t
    sensor_long = start_long + (end_long - start_long) * t
    
    # Append the calculated positions along with the original sensor data to the list
    converted_sensors_data.append({
        'sensor_id': int(sensor['sensor_id']),
        'start_node': int(start_node),
        'end_node': int(end_node),
        'world_lat': sensor_lat,
        'world_long': sensor_long
    })

# Convert the list to a DataFrame
converted_sensors_df = pd.DataFrame(converted_sensors_data)

# Save the converted data to a new CSV file
converted_sensors_df.to_csv('data/sensors_converted.csv', index=False)
