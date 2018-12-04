# -*- coding: utf-8 -*-
"""


@author: Christian Talmadge
Convert CSV to GeoJSON
"""

import csv
import json
import os
from collections import OrderedDict

li = []

#read csv
with open('/home/bseylerg/public_html/msc/MSC-Heatmap/csv-updater/nursingHomeData.csv', 'r') as csvfile:
    reader = csv.reader(csvfile, delimiter=',')

    #skip the first row(header)
    next(reader)
    for latitude, longitude, address, name, fine_amount, num_fines, scope  in reader:
        d = OrderedDict()
        
        #append to geojson format. Note that the way that markers.js loads in the data,
        #it requires the properties to be uppercase.
        d['type'] = 'Feature'
        d['properties'] = {
            'PROVNAME': name,
            'ADDRESS': address,
            'FINE_AMOUNT': float(fine_amount),
            'NUM_FINES': float(num_fines),
            'SCOPE': scope
            
        }
        #This stores the point to place the marker on the map at.
        d['geometry'] = {
            'type': 'Point',
            'coordinates': [float(longitude), float(latitude)]
        }
        li.append(d)

d = OrderedDict()
d['type'] = 'FeatureCollection'
d['features'] = li

#write to geojson file. We use the OS package to write the file to the directory above
with open(os.path.join(os.pardir, "map.geojson"), 'w') as f:
    f.write(json.dumps(d, sort_keys=False, indent=4))