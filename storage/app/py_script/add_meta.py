from PyPDF2 import PdfReader, PdfWriter
import sys
import json
import os

argv = {}


for i,arg in enumerate(sys.argv):
    if "-" in arg:
        argv[arg.replace("-", "")] = sys.argv[i+1]
        
# print(argv)

# Add the metadata
raw_meta = argv["meta_arr"].replace("\"", "")
raw_meta = raw_meta.replace("'", "\"")
meta_data = json.loads(raw_meta)
# print(raw_meta)

reader = PdfReader(argv["path"])
writer = PdfWriter()

meta_data['/dsfile_type'] = "part"

# Add all pages to the writer
i = 0
for page in reader.pages:
    i=i+1
    onePageWriter = PdfWriter()
    onePageWriter.add_page(page)
    onePageWriter.add_metadata(meta_data)
    _path = os.path.dirname(argv["path"]) +"\\"+ str(i) +".pdf"
    print(_path)
    with open(_path, "wb") as f:
        onePageWriter.write(f)
    writer.add_page(page)

meta_data['/dsfile_type'] = "orginal"
writer.add_metadata(meta_data)

# Save the new PDF to a file
with open(argv["path"], "wb") as f:
    writer.write(f)
    
# print(argv)