from PyPDF2 import PdfReader, PdfWriter
import sys
import json

argv = {}


for i,arg in enumerate(sys.argv):
    if "-" in arg:
        argv[arg.replace("-", "")] = sys.argv[i+1]
        
print(argv)

reader = PdfReader(argv["path"])
writer = PdfWriter()

# Add all pages to the writer
for page in reader.pages:
    writer.add_page(page)

# Add the metadata
raw_meta = argv["meta_arr"].replace("\"", "")
raw_meta = raw_meta.replace("'", "\"")
print(raw_meta)
meta_data = json.loads(raw_meta)
writer.add_metadata(meta_data)

# Save the new PDF to a file
with open(argv["path"], "wb") as f:
    writer.write(f)
    
print(argv)