#!/usr/bin/env python3
"""
Injecte les captures d'écran dans les placeholders du rapport PDF.

Mapping (placeholders identifiés via PyMuPDF) :
  Page 29 (idx 28) — xref 248 — Figure 4.1 (Page d'accueil)
  Page 30 (idx 29) — xref 249 — Figure 4.2 (Connexion)
  Page 30 (idx 29) — xref 250 — Figure 4.3 (Tableau de bord)
  Page 31 (idx 30) — xref 251 — Figure 4.4 (Page de gestion)
  Page 32 (idx 31) — xref 252 — Figure 4.5 (Mobile responsive)
"""
import fitz
from pathlib import Path

SRC = "/Users/mac/Downloads/Rapport_Gestion_des_Admissions_Professionnel.pdf"
DST = "/Users/mac/Downloads/Rapport_Gestion_des_Admissions_avec_captures.pdf"
CAPTURES = Path("/tmp/rapport_captures")

REPLACEMENTS = [
    (28, 248, CAPTURES / "welcome.png",   "Figure 4.1 — Page d'accueil"),
    (29, 249, CAPTURES / "login.png",     "Figure 4.2 — Connexion"),
    (29, 250, CAPTURES / "dashboard.png", "Figure 4.3 — Tableau de bord"),
    (30, 251, CAPTURES / "gestion.png",   "Figure 4.4 — Page de gestion"),
    (31, 252, CAPTURES / "mobile.png",    "Figure 4.5 — Mobile responsive"),
]

doc = fitz.open(SRC)
print(f"Ouverture : {SRC} ({len(doc)} pages)\n")

for page_idx, xref, img_path, label in REPLACEMENTS:
    if not img_path.exists():
        print(f"  ✗ {label} : fichier introuvable {img_path}")
        continue

    page = doc[page_idx]
    try:
        page.replace_image(xref, filename=str(img_path))
        print(f"  ✓ {label} — page {page_idx + 1}, xref {xref} ← {img_path.name} ({img_path.stat().st_size // 1024} KB)")
    except Exception as e:
        print(f"  ✗ {label} : {e}")

doc.save(DST, garbage=4, deflate=True, clean=True)
doc.close()

print(f"\nFichier généré : {DST}")
print(f"Taille : {Path(DST).stat().st_size // 1024} KB")
