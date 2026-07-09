import ftplib
import os

FTP_HOST = "46.202.182.199"
FTP_USER = "u815786501.ehtshaamrajpoot.com"
FTP_PASS = "&.8&@:@c%QcVrFi"
TARGET_DIR = "public_html"
LOCAL_DIR = r"c:\Users\officialum1 llc\Desktop\ehtshaamrajpoot"

def ensure_dir(ftp, dirname):
    try:
        ftp.cwd(dirname)
    except ftplib.error_perm:
        try:
            ftp.mkd(dirname)
            ftp.cwd(dirname)
        except ftplib.error_perm as e:
            print(f"Failed creating directory {dirname}: {e}")

def upload_recursive(ftp, local_path):
    for item in os.listdir(local_path):
        if item in ['.git', 'deploy.py', 'update_about.sql', '.system_generated', 'website_ready.zip', 'db.php']:
            continue
            
        local_item = os.path.join(local_path, item)
        if os.path.isfile(local_item):
            print(f"Uploading {item}...")
            try:
                with open(local_item, 'rb') as f:
                    ftp.storbinary(f'STOR {item}', f)
            except Exception as e:
                print(f"Failed to upload {item}: {e}")
        elif os.path.isdir(local_item):
            print(f"Entering directory {item}...")
            ensure_dir(ftp, item)
            upload_recursive(ftp, local_item)
            ftp.cwd('..')

try:
    print(f"Connecting to {FTP_HOST}...")
    ftp = ftplib.FTP(FTP_HOST)
    ftp.login(FTP_USER, FTP_PASS)
    print("Login successful.")
    
    # Try changing to public_html
    try:
        ftp.cwd(TARGET_DIR)
        print(f"Changed to directory {TARGET_DIR}")
    except ftplib.error_perm:
        print(f"Warning: Could not change to {TARGET_DIR}. Uploading to current directory.")
        
    upload_recursive(ftp, LOCAL_DIR)
    
    ftp.quit()
    print("Deployment completed successfully!")
except Exception as e:
    print(f"Error during deployment: {e}")
