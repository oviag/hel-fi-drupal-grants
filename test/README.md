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
- `robot -d logs/ --variablefile=variables/local-env.yaml tests/*.robot`

Change `*-env.yaml` based on which environment you want to run the tests.
