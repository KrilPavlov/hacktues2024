import pandas as pd

reference_points = [
    {'pixel_x': 830, 'pixel_y': 450, 'world_lat': 41.818890, 'world_long': 23.473719},  # Reference point 1
    {'pixel_x': 920, 'pixel_y': 713, 'world_lat': 41.548497, 'world_long': 23.561021}  # Reference point 2
]

# Calculate scale and offset
dx = reference_points[1]['pixel_x'] - reference_points[0]['pixel_x']
dy = reference_points[1]['pixel_y'] - reference_points[0]['pixel_y']
dlat = reference_points[1]['world_lat'] - reference_points[0]['world_lat']
dlong = reference_points[1]['world_long'] - reference_points[0]['world_long']

scale_x = dlat / dx
scale_y = dlong / dy
offset_x = reference_points[0]['world_lat'] - (scale_x * reference_points[0]['pixel_x'])
offset_y = reference_points[0]['world_long'] - (scale_y * reference_points[0]['pixel_y'])

# Function to convert pixel coordinates to world coordinates
def pixel_to_world(pixel_x, pixel_y):
    world_lat = scale_x * pixel_x + offset_x
    world_long = scale_y * pixel_y + offset_y
    return world_lat, world_long

# Load your datasets
sensors_df = pd.read_csv('data/sensors.csv')
node_positions_df = pd.read_csv('data/node_positions.csv')

# Apply the conversion
sensors_df['world_lat'], sensors_df['world_long'] = zip(*sensors_df.apply(lambda row: pixel_to_world(row['position_x'], row['position_y']), axis=1))
node_positions_df['world_lat'], node_positions_df['world_long'] = zip(*node_positions_df.apply(lambda row: pixel_to_world(row['X Position'], row['Y Position']), axis=1))

# Save the converted data
sensors_df.to_csv('data/sensors_converted.csv', index=False)
node_positions_df.to_csv('data/node_positions_converted.csv', index=False)

print("Conversion completed and files saved.")
