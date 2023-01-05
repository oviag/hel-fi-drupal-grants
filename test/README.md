# Robot tests

## Requirements

- python3
- pip
- chromedriver

## Install

- `cd test`
- `python3 -m venv env`
- `source env/bin/activate`
- `pip install -r requirements.txt`

## Running

- `cd test`
- `source env/bin/activate`
- `TEST_BASEURL=https://hel-fi-drupal-grant-applications.docker.so/ robot -d logs/ tests/*.robot`
