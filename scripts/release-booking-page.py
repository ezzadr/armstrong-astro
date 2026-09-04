"""Atomic, backup-verified release of the public Book Online page only."""
import base64
import hashlib
import json
import os
from pathlib import Path
import re
import sys
import uuid
import zlib

ROOT = Path('/home/master/applications/btfdkcdpdw/public_html')

def digest(data):
    return hashlib.sha256(data).hexdigest()

def publish(bundle):
    target = ROOT / 'book-online/index.html'
    if ROOT.resolve() != ROOT or target.resolve() != target or not target.is_file():
        raise RuntimeError('Unexpected website target: root=' + str(ROOT.resolve()) + '; page=' + str(target.resolve()))
    content = zlib.decompress(base64.b64decode(bundle['page'], validate=True))
    if digest(content) != bundle['sha256']:
        raise RuntimeError('Release checksum mismatch')
    for asset, checksum in bundle['assets'].items():
        if not re.fullmatch(r'_astro/[A-Za-z0-9_.-]+', asset):
            raise RuntimeError('Unapproved asset path')
        path = ROOT / asset
        if path.resolve() != path or not path.is_file() or digest(path.read_bytes()) != checksum:
            raise RuntimeError('Required asset missing or different; release stopped')
    backups = ROOT.parent / '.booking-page-backups'
    if backups.is_symlink():
        raise RuntimeError('Unsafe backup directory')
    backups.mkdir(mode=0o700, exist_ok=True)
    os.chmod(backups, 0o700)
    release = backups / uuid.uuid4().hex
    release.mkdir(mode=0o700)
    previous = target.read_bytes()
    backup = release / 'index.html'
    backup.write_bytes(previous)
    os.chmod(backup, 0o600)
    if digest(backup.read_bytes()) != digest(previous):
        raise RuntimeError('Backup verification failed')
    metadata = {'before': digest(previous), 'after': bundle['sha256'], 'commit': bundle['commit'], 'target': str(target)}
    (release / 'checksums.json').write_text(json.dumps(metadata), encoding='utf8')
    os.chmod(release / 'checksums.json', 0o600)
    if digest(target.read_bytes()) != metadata['before']:
        raise RuntimeError('Concurrent website change; release stopped')
    temporary = target.parent / ('.booking-release-' + uuid.uuid4().hex + '.tmp')
    try:
        with temporary.open('xb') as output:
            output.write(content)
            output.flush()
            os.fsync(output.fileno())
        os.chmod(temporary, target.stat().st_mode & 0o777)
        os.replace(temporary, target)
        if digest(target.read_bytes()) != bundle['sha256']:
            raise RuntimeError('Installed page checksum mismatch; rollback copy retained')
    finally:
        if temporary.exists():
            temporary.unlink()
    print('Verified public-page rollback copy: ' + str(backup))
    print('Installed booking page SHA256: ' + bundle['sha256'])

if __name__ == '__main__':
    publish(json.loads(base64.b64decode(sys.argv[1], validate=True)))
