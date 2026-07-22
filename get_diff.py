import json

log_path = '/Users/fahamayoub/.gemini/antigravity/brain/aebd6ee9-7b94-408c-b704-71fc6094af52/.system_generated/logs/overview.txt'

with open(log_path, 'r') as f:
    for line in f:
        if '"step_index":2612' in line:
            data = json.loads(line)
            print(data['content'])
            break
