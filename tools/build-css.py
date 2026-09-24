#!/usr/bin/env python3
"""Compile clarity-copiers/src/style.src.css -> clarity-copiers/style.css.

Tokens (design px on the 1920 XD canvas):
  {N}  -> calc(N * var(--u))                       scales 1:1 with the mockup
  [N]  -> max(<72% of N>px, calc(N * var(--u)))    same, with a readability floor
"""
import pathlib
import re

ROOT = pathlib.Path(__file__).resolve().parent.parent / "clarity-copiers"
src = (ROOT / "src" / "style.src.css").read_text()


def fmt(n):
    return ("%g" % n)


out = re.sub(r"\{(-?\d+(?:\.\d+)?)\}", lambda m: f"calc({m.group(1)} * var(--u))", src)
out = re.sub(
    r"\[(\d+(?:\.\d+)?)\]",
    lambda m: f"max({fmt(round(float(m.group(1)) * 0.72, 1))}px, calc({m.group(1)} * var(--u)))",
    out,
)
(ROOT / "style.css").write_text(out)
print("wrote", ROOT / "style.css", len(out), "bytes")
