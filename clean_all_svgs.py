import os
import re

key_svg = """<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>"""
cut_svg = """<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242 3 3 0 014.242 0zM14.121 9.879L19 5m-7 7L9.121 9.879a3 3 0 10-4.242 4.242 3 3 0 004.242 0z"/></svg>"""
wrench_svg = """<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>"""
lightning_svg = """<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>"""

clock_svg = """<svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>"""
shop_svg = """<svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>"""
dollar_svg = """<svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>"""
shield_svg = """<svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>"""

pages_dir = os.path.join(os.getcwd(), 'src', 'pages')
for f in os.listdir(pages_dir):
    if not f.endswith('.astro'):
        continue
    filepath = os.path.join(pages_dir, f)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as fp:
        c = fp.read()

    # Fix all corrupted characters
    c = c.replace('\ufffd', '&ndash;')
    c = re.sub(r'15[\?\ufffd]25', '15–25', c)
    c = re.sub(r'20[\?\ufffd]40', '20–40', c)
    c = re.sub(r'150[\?\ufffd]350', '150–$350', c)
    c = re.sub(r'150[\?\ufffd]300', '150–$300', c)
    c = re.sub(r'300[\?\ufffd]700', '300–$700', c)
    c = re.sub(r'400[\?\ufffd]800', '400–$800', c)
    c = re.sub(r'500[\?\ufffd]1,000', '500–$1,000', c)
    c = re.sub(r'Save \$150[\?\ufffd]', 'Save $150–', c)
    c = re.sub(r'Save \$300[\?\ufffd]', 'Save $300–', c)
    c = re.sub(r'Save \$400[\?\ufffd]', 'Save $400–', c)
    c = re.sub(r'Save \$500[\?\ufffd]', 'Save $500–', c)

    # Fix Service Card Icons
    c = re.sub(
        r'<div class="w-12 h-12[^"]*">[\?\s\ufffd&#;0-9]*</div>\s*<h3([^>]*)>([^<]*Laser[^<]*|[^<]*Cut[^<]*)</h3>',
        r'<div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">' + cut_svg + r'</div><h3\1>\2</h3>',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-12 h-12[^"]*">[\?\s\ufffd&#;0-9]*</div>\s*<h3([^>]*)>([^<]*Ignition[^<]*|[^<]*Repair[^<]*|[^<]*Bench[^<]*)</h3>',
        r'<div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">' + wrench_svg + r'</div><h3\1>\2</h3>',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-12 h-12[^"]*">[\?\s\ufffd&#;0-9]*</div>\s*<h3([^>]*)>([^<]*Emergency[^<]*|[^<]*Lost[^<]*|[^<]*Fast[^<]*)</h3>',
        r'<div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">' + lightning_svg + r'</div><h3\1>\2</h3>',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-12 h-12[^"]*">[\?\s\ufffd&#;0-9]*</div>\s*<h3([^>]*)>([^<]*Chrome[^<]*|[^<]*Smart[^<]*|[^<]*Fob[^<]*|[^<]*Key[^<]*)</h3>',
        r'<div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">' + key_svg + r'</div><h3\1>\2</h3>',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-12 h-12[^"]*">[\?\s\ufffd&#;0-9]*</div>',
        r'<div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4">' + key_svg + r'</div>',
        c, flags=re.IGNORECASE
    )

    # Fix Metric Strip Icons
    c = re.sub(
        r'<div class="w-10 h-10[^"]*bg-amber-50[^"]*">[\s\S]*?</div>(\s*<div><strong[^>]*>15[–\-]25 Min)',
        r'<div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shadow-sm">' + clock_svg + r'</div>\1',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-10 h-10[^"]*bg-emerald-50[^"]*">[\s\S]*?</div>(\s*<div><strong[^>]*>208 Thompson)',
        r'<div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shadow-sm">' + shop_svg + r'</div>\1',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-10 h-10[^"]*bg-amber-50[^"]*">[\s\S]*?</div>(\s*<div><strong[^>]*>Save)',
        r'<div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shadow-sm">' + dollar_svg + r'</div>\1',
        c, flags=re.IGNORECASE
    )
    c = re.sub(
        r'<div class="w-10 h-10[^"]*bg-slate-100[^"]*">[\s\S]*?</div>(\s*<div><strong[^>]*>TN License)',
        r'<div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shadow-sm">' + shield_svg + r'</div>\1',
        c, flags=re.IGNORECASE
    )

    # General Cleanup of any remaining ??? or ??
    c = c.replace('???', '&#10003;')
    c = c.replace('??', '&#10003;')

    with open(filepath, 'w', encoding='utf-8') as fp:
        fp.write(c)

print('Cleaned all files with beautiful inline SVGs and clean encoding!')
