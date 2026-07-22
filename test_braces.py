import re

with open('functions-snippet-complete.php', 'r') as f:
    content = f.read()

def check_php_blocks(content):
    php_blocks = re.findall(r'<\?php(.*?)(?:\?>|$)', content, re.DOTALL)
    for i, block in enumerate(php_blocks):
        open_braces = block.count('{')
        close_braces = block.count('}')
        if open_braces != close_braces:
            print(f"Mismatch in PHP block {i}: {{ = {open_braces}, }} = {close_braces}")
            
check_php_blocks(content)
