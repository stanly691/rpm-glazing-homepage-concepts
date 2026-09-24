#!/usr/bin/env python3
"""Compile clarity-copiers/src/*.src.css -> clarity-copiers/style.css.

  src/style.src.css   main stylesheet (keeps the WordPress theme header)
  src/fonts.src.css   self-hosted @font-face rules, inserted after the header
  src/system.src.css  design-system tokens + global behaviours, inserted at /*@@INNER@@*/
  src/inner.src.css   inner-page sections, inserted after the system layer

Tokens (design px on the 1920 XD canvas):
  {N}  -> calc(N * var(--u))                       scales 1:1 with the mockup
  [N]  -> max(<floor>, calc(N * var(--u)))  same, never below 16px (N>=20) / 14px (N 15-19)

Output is minified (comments/whitespace) except the theme header comment.
"""
import pathlib
import re

ROOT = pathlib.Path(__file__).resolve().parent.parent / "clarity-copiers"
SRC = ROOT / "src"

main = (SRC / "style.src.css").read_text()
fonts = (SRC / "fonts.src.css").read_text()
inner = (SRC / "inner.src.css").read_text()
system = (SRC / "system.src.css").read_text()

header_end = main.index("*/") + 2
header, body = main[:header_end], main[header_end:]
body = fonts + "\n" + body.replace("/*@@INNER@@*/", system + "\n" + inner)


def fmt(n):
    return "%g" % n


body = re.sub(r"\{(-?\d+(?:\.\d+)?)\}", lambda m: f"calc({m.group(1)} * var(--u))", body)
def floor_px(n):
    """Readability floor for [N] text: body copy never below 16px, small text never below 14px."""
    if n >= 20:
        return 16
    if n >= 15:
        return 14
    return round(n * 0.72, 1)


body = re.sub(
    r"\[(\d+(?:\.\d+)?)\]",
    lambda m: f"max({fmt(floor_px(float(m.group(1))))}px, calc({m.group(1)} * var(--u)))",
    body,
)

# Minify: drop comments, collapse whitespace around punctuation.
body = re.sub(r"/\*.*?\*/", "", body, flags=re.S)
body = re.sub(r"\s+", " ", body)
body = re.sub(r"\s*([{};,>])\s*", r"\1", body)
body = re.sub(r";}", "}", body)

out = header + "\n" + body.strip() + "\n"
(ROOT / "style.css").write_text(out)
print("wrote", ROOT / "style.css", len(out), "bytes")
