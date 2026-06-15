#!/usr/bin/env python3
"""Trouver les placeholders d'images sur les pages 29-32."""
import fitz

PDF = "/Users/mac/Downloads/Rapport_Gestion_des_Admissions_Professionnel.pdf"

doc = fitz.open(PDF)

for page_num in [28, 29, 30, 31, 32]:  # 0-indexed: 29 -> index 28
    page = doc[page_num]
    print(f"\n===== PAGE {page_num + 1} =====")
    print(f"Taille page: {page.rect}")

    # Texte
    text = page.get_text().strip()
    print(f"\nTexte (300 premiers car):\n{text[:300]}")

    # Images avec positions
    print(f"\nImages avec positions:")
    image_list = page.get_image_info(xrefs=True)
    for idx, info in enumerate(image_list):
        bbox = info.get("bbox")
        xref = info.get("xref")
        w = info.get("width", "?")
        h = info.get("height", "?")
        if bbox:
            box = fitz.Rect(bbox)
            print(f"  [{idx}] xref={xref} size={w}x{h} px @ bbox=({box.x0:.0f},{box.y0:.0f})-({box.x1:.0f},{box.y1:.0f}) [{box.width:.0f}x{box.height:.0f} pts]")

doc.close()
