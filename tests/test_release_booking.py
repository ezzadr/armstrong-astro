import base64
import importlib.util
import json
from pathlib import Path
import tempfile
import unittest
from unittest.mock import patch
import zlib

spec = importlib.util.spec_from_file_location('release', Path(__file__).parents[1] / 'scripts/release-booking-page.py')
release = importlib.util.module_from_spec(spec)
spec.loader.exec_module(release)

class ReleaseTests(unittest.TestCase):
    def setUp(self):
        self.temp = tempfile.TemporaryDirectory()
        self.addCleanup(self.temp.cleanup)
        self.root = Path(self.temp.name).resolve() / 'public_html'
        (self.root / 'book-online').mkdir(parents=True)
        self.target = self.root / 'book-online/index.html'
        self.target.write_bytes(b'original public page')
        self.bundle = {'page': base64.b64encode(zlib.compress(b'new public page')).decode(),
                       'sha256': release.digest(b'new public page'), 'commit': 'a' * 40, 'assets': {}}

    def publish(self):
        with patch.object(release, 'ROOT', self.root):
            release.publish(self.bundle)

    def test_backup_verified_and_only_booking_page_changes(self):
        other = self.root / 'other.html'
        other.write_bytes(b'untouched')
        self.publish()
        self.assertEqual(self.target.read_bytes(), b'new public page')
        self.assertEqual(other.read_bytes(), b'untouched')
        backup = next((self.root.parent / '.booking-page-backups').glob('*/index.html'))
        self.assertEqual(backup.read_bytes(), b'original public page')
        metadata = json.loads(backup.with_name('checksums.json').read_text())
        self.assertEqual(metadata['before'], release.digest(backup.read_bytes()))

    def test_bad_checksum_stops_before_backup_or_changes(self):
        self.bundle['sha256'] = 'wrong'
        with self.assertRaises(RuntimeError):
            self.publish()
        self.assertEqual(self.target.read_bytes(), b'original public page')
        self.assertFalse((self.root.parent / '.booking-page-backups').exists())

    def test_missing_asset_stops_before_changes(self):
        self.bundle['assets'] = {'_astro/missing.css': 'unknown'}
        with self.assertRaises(RuntimeError):
            self.publish()
        self.assertEqual(self.target.read_bytes(), b'original public page')

    def test_asset_escape_is_rejected(self):
        self.bundle['assets'] = {'../outside': 'unknown'}
        with self.assertRaises(RuntimeError):
            self.publish()
        self.assertEqual(self.target.read_bytes(), b'original public page')

    def test_failed_atomic_replace_preserves_page_and_backup(self):
        with patch.object(release.os, 'replace', side_effect=OSError('test')):
            with self.assertRaises(OSError):
                self.publish()
        self.assertEqual(self.target.read_bytes(), b'original public page')
        self.assertEqual(len(list((self.root.parent / '.booking-page-backups').glob('*/index.html'))), 1)
        self.assertEqual(list(self.target.parent.glob('*.tmp')), [])
