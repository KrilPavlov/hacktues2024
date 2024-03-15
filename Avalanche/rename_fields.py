import os
import json

# Define the path to the directory containing the reports
directory_path = 'data/reports'

# Define the mappings of old field names to new field names
field_mappings = {
    "Danger Level on bellow treeline": "Danger1",
    "Danger Level on treeline": "Danger2",
    "Danger Level in alpine layer": "Danger3",
    "Wind slab danger (if any) bellow treeline": "WindSlab1",
    "Wind slab danger (if any) on treeline": "WindSlab2",
    "Wind slab danger (if any) in alpine layer": "WindSlab3",
    "Cronicle fall danger (if any) bellow treeline": "CronicleFall1",
    "Cronicle fall danger (if any) on treeline": "CronicleFall2",
    "Cronicle fall danger (if any) in alpine layer": "CronicleFall3"
}

# Walk through the directory and its subdirectories
for root, dirs, files in os.walk(directory_path):
    for file in files:
        if file.endswith('.json'):
            file_path = os.path.join(root, file)
            # Read the JSON data
            with open(file_path, 'r', encoding='utf-8') as f:
                data = json.load(f)

            # Rename the fields based on the mappings
            updated_data = {}
            for key, value in data.items():
                new_key = field_mappings.get(key, key)  # Get the new key if it exists, otherwise use the old key
                updated_data[new_key] = value

            # Write the modified data back to the file
            with open(file_path, 'w', encoding='utf-8') as f:
                json.dump(updated_data, f, ensure_ascii=False, indent=4)

print("All files have been processed.")
