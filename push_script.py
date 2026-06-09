import subprocess
import os

os.chdir(r'D:\frontend_inventrack')

print("=" * 70)
print("ATTEMPTING GIT PUSH")
print("=" * 70)

# First check status
print("\n1. Checking git status:")
subprocess.run(['git', 'status', '--short', '--branch'])

print("\n2. Checking current branch:")
subprocess.run(['git', 'branch', '--show-current'])

print("\n3. Checking remotes:")
subprocess.run(['git', 'remote', '-v'])

print("\n4. Attempting push to main:")
print("-" * 70)
result = subprocess.run(
    ['git', 'push', '-u', 'origin', 'main', '-v'],
    capture_output=True,
    text=True,
    timeout=60
)

print("STDOUT:")
print(result.stdout if result.stdout else "(empty)")
print("\nSTDERR:")
print(result.stderr if result.stderr else "(empty)")
print("\nReturn Code:", result.returncode)

if result.returncode == 0:
    print("\n✓ PUSH SUCCESSFUL!")
else:
    print(f"\n✗ PUSH FAILED with code {result.returncode}")
    print("\nTrying alternative push with HTTPS explicit credentials check...")
    result2 = subprocess.run(['git', 'remote', 'get-url', 'origin'], capture_output=True, text=True)
    print("Remote URL:", result2.stdout)
