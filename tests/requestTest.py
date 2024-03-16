import requests

# The URL you want to make a POST request to
url = 'http://127.0.0.1:8000/post'

# The data you want to send, formatted as a dictionary
data = {
    'sensore_id': 'tester',
    'group_size': 1,
    'direction' : 1,
    'speed' : 1.23, 
}

# Making the POST request
response = requests.post(url, json=data)

# Checking if the request was successful
if response.status_code == 200:
    print('Success!')
    print(response.text)  # Print the response text (or data) from the server
else:
    print('Failed to make the request.')
    print(f'Status code: {response.status_code}')
    # print(response.text)  # Print any error messages or details provided by the server
