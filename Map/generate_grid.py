# Define the endpoints
nw_lat, nw_long = 41.9, 23.3
sw_lat, sw_long = 41.6, 23.3
se_lat, se_long = 41.6, 23.6
ne_lat, ne_long = 41.9, 23.6

# Define the number of squares per side
num_squares_side = 6

# Calculate step sizes for latitude and longitude
lat_step = (nw_lat - sw_lat) / num_squares_side
long_step = (ne_long - nw_long) / num_squares_side

# Generate the four points for each square
grid_points = []

for row in range(num_squares_side):
    for col in range(num_squares_side):
        # Calculate the coordinates of the current square's corners
        top_left = (nw_lat - row * lat_step, nw_long + col * long_step)
        top_right = (nw_lat - row * lat_step, nw_long + (col + 1) * long_step)
        bottom_left = (nw_lat - (row + 1) * lat_step, nw_long + col * long_step)
        bottom_right = (nw_lat - (row + 1) * lat_step, nw_long + (col + 1) * long_step)
        
        # Store the coordinates
        grid_points.append((top_left, top_right, bottom_left, bottom_right))

import pandas as pd

# Convert each square's points into a flat list representing the four corners
flattened_squares = [item for square in grid_points for item in (square[0] + square[1] + square[2] + square[3])]

# Reshape the list into a 2D list where each sublist represents the coordinates of a square's corners
squares_as_rows = [flattened_squares[i:i+8] for i in range(0, len(flattened_squares), 8)]

# Create a DataFrame from the 2D list
df_squares = pd.DataFrame(squares_as_rows, columns=['Lat1', 'Long1', 'Lat2', 'Long2', 'Lat3', 'Long3', 'Lat4', 'Long4'])

# Save the DataFrame to a CSV file, without the header and index
csv_file_path_squares = "data/grid_squares.csv"
df_squares.to_csv(csv_file_path_squares, header=False, index=False)

csv_file_path_squares


