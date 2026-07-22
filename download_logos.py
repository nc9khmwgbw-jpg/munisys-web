import urllib.request
import json
import os

DB_PATH = './src/data/db.json'
LOGOS_DIR = './public/images/logos'

if not os.path.exists(LOGOS_DIR):
    os.makedirs(LOGOS_DIR)

def create_text_svg(text, color):
    return f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60">
<text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-weight="bold" font-size="24" fill="{color}">{text}</text>
</svg>"""

def download_image(url, filename, fallback_text, fallback_color):
    filepath = os.path.join(LOGOS_DIR, filename)
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)'})
        with urllib.request.urlopen(req, timeout=10) as response, open(filepath, 'wb') as out_file:
            data = response.read()
            if len(data) > 0:
                out_file.write(data)
                return f'/images/logos/{filename}'
    except Exception as e:
        print(f"Failed to download {url}: {e}")
    
    # Fallback to Text SVG
    svg_filename = filename.rsplit('.', 1)[0] + '.svg'
    svg_filepath = os.path.join(LOGOS_DIR, svg_filename)
    with open(svg_filepath, 'w') as out_file:
        out_file.write(create_text_svg(fallback_text, fallback_color))
    return f'/images/logos/{svg_filename}'

print("Downloading and processing logos locally...")

# Technology Partners
# We only need to touch VMware and Fortinet
vmware_path = download_image('https://logo.clearbit.com/vmware.com', 'vmware.png', 'vmware', '#607078')
fortinet_path = download_image('https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Fortinet_logo.svg/512px-Fortinet_logo.svg.png', 'fortinet.png', 'FORTINET', '#E31837')

# Customer References
bp_path = download_image('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Logo_BCP.svg/512px-Logo_BCP.svg.png', 'bp.png', 'Banque Populaire', '#d88b22')
boa_path = download_image('https://www.bankofafrica.ma/sites/default/files/BANKOFAFRICA-LOGOO.png', 'boa.png', 'BANK OF AFRICA', '#003b70')
bam_path = download_image('https://logo.clearbit.com/bkam.ma', 'bam.png', 'Bank Al-Maghrib', '#1a4e2b')
awb_path = download_image('https://logo.clearbit.com/attijariwafabank.com', 'awb.png', 'Attijariwafa', '#d88b22')
cdg_path = download_image('https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/CDG.svg/512px-CDG.svg.png', 'cdg.png', 'CDG', '#1a4e2b')
oncf_path = download_image('https://upload.wikimedia.org/wikipedia/commons/0/05/Logo-oncf.png', 'oncf.png', 'ONCF', '#f37021')
ocp_path = download_image('https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/OCP_Group.svg/512px-OCP_Group.svg.png', 'ocp.png', 'OCP', '#72b626')
marbio_path = download_image('https://logo.clearbit.com/marbio.ma', 'marbio.png', 'Marbio', '#3f80c0')
lafarge_path = download_image('https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/LafargeHolcim_logo.svg/512px-LafargeHolcim_logo.svg.png', 'lafarge.png', 'LafargeHolcim', '#000000')
inwi_path = download_image('https://logo.clearbit.com/inwi.ma', 'inwi.png', 'inwi', '#e3000f')

with open(DB_PATH, 'r', encoding='utf-8') as f:
    data = json.load(f)

# Update Technology Partners
for lang in ['en', 'fr', 'ar']:
    if lang in data and 'technologyPartners' in data[lang]:
        for p in data[lang]['technologyPartners']['partners']:
            if 'vmware' in p['name'].lower() or 'في إم وير' in p['name']:
                p['logo'] = vmware_path
            elif 'fortinet' in p['name'].lower() or 'فورتينت' in p['name']:
                p['logo'] = fortinet_path

# Update Customer References
customer_mapping = {
    'bp': bp_path,
    'boa': boa_path,
    'bam': bam_path,
    'awb': awb_path,
    'cdg': cdg_path,
    'oncf': oncf_path,
    'ocp': ocp_path,
    'marbio': marbio_path,
    'lafarge': lafarge_path,
    'inwi': inwi_path
}

for lang in ['en', 'fr', 'ar']:
    if lang in data and 'customerReferences' in data[lang]:
        refs = data[lang]['customerReferences']['references']
        if len(refs) == 10:
            refs[0]['logo'] = bp_path
            refs[1]['logo'] = boa_path
            refs[2]['logo'] = bam_path
            refs[3]['logo'] = awb_path
            refs[4]['logo'] = cdg_path
            refs[5]['logo'] = oncf_path
            refs[6]['logo'] = ocp_path
            refs[7]['logo'] = marbio_path
            refs[8]['logo'] = lafarge_path
            refs[9]['logo'] = inwi_path

with open(DB_PATH, 'w', encoding='utf-8') as f:
    json.dump(data, f, indent=2, ensure_ascii=False)

print("DB successfully updated to use local images.")
