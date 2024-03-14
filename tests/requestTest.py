import requests

# The URL you want to make a POST request to
url = 'http://192.168.206.240'

# The data you want to send, formatted as a dictionary
data = {
    'key': 'value',  # Replace these with the actual data keys and values you need to send
    'another_key': 'another_value',
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
    print(response.text)  # Print any error messages or details provided by the server
