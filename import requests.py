import requests
from fpdf import FPDF
from bs4 import BeautifulSoup
import os

# Data manual (kode wilayah)
kode_provinsi = "33"  # Jawa Tengah
kode_kabupaten = "3326"  # Pekalongan
kode_kecamatan = "332612"  # Wonopringgo
kode_desa = "3326122005"  # Kwagean
kode_tps = "3326122005005"  # TPS 005

# Base URL
base_url = f"https://pilkada2024.kpu.go.id/pilgub/jawa-tengah/pekalongan/{kode_kecamatan}/{kode_desa}/{kode_tps}"

# Direktori penyimpanan
output_dir = "downloaded_images"
os.makedirs(output_dir, exist_ok=True)

# PDF output
pdf_name = f"TPS_{kode_tps}.pdf"

# Step 1: Dapatkan halaman HTML
try:
    response = requests.get(base_url)
    response.raise_for_status()
    soup = BeautifulSoup(response.text, 'html.parser')
except requests.exceptions.RequestException as e:
    print(f"Gagal mendapatkan data dari {base_url}: {e}")
    exit()

# Step 2: Analisis struktur dan ambil URL gambar dari HTML
image_ids = []
for img_tag in soup.find_all("img"):
    image_url = img_tag.get("src")
    if "sirekapilkkada-obj-data" in image_url:
        image_ids.append(image_url)

# Step 3: Unduh gambar berdasarkan URL
image_files = []
for i, image_url in enumerate(image_ids):
    file_name = f"{output_dir}/image_{i+1}.jpg"
    try:
        response = requests.get(image_url)
        response.raise_for_status()
        with open(file_name, 'wb') as file:
            file.write(response.content)
        image_files.append(file_name)
        print(f"Berhasil mengunduh {file_name}")
    except requests.exceptions.RequestException as e:
        print(f"Gagal mengunduh {image_url}: {e}")

# Step 4: Gabungkan ke PDF
if image_files:
    pdf = FPDF()
    for image in image_files:
        pdf.add_page()
        pdf.image(image, x=10, y=10, w=190)
    pdf.output(pdf_name)
    print(f"PDF berhasil dibuat: {pdf_name}")
else:
    print("Tidak ada gambar yang berhasil diunduh untuk dibuat PDF.")
