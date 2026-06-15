#!/usr/bin/env python3
"""Analyser la structure du PDF pour repérer les pages et placeholders d'images."""
import fitz
import sys

PDF = "/Users/mac/Downloads/Rapport_Gestion_des_Admissions_Professionnel.pdf"

doc = fitz.open(PDF)
print(f"=== {len(doc)} pages ===\n")

for i, page in enumerate(doc, start=1):
    text = page.get_text().strip()
    images = page.get_images(full=True)
    drawings = page.get_drawings()

    snippet = text[:200].replace("\n", " | ")
    print(f"--- Page {i} ---")
    print(f"Text: {snippet}")
    print(f"Images: {len(images)} | Drawings: {len(drawings)}")

    # Repérer les rectangles vides (placeholders typiques: gros rectangles sans texte dedans)
    big_rects = []
    for d in drawings:
        rect = d.get("rect")
        if rect:
            w = rect.width
            h = rect.height
            if w > 200 and h > 100:
                big_rects.append((round(w), round(h), round(rect.x0), round(rect.y0)))
    if big_rects:
        print(f"Grands rectangles (w×h @ x,y): {big_rects[:5]}")
    print()

doc.close()
