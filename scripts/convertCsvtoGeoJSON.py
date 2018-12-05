# -*- coding: utf-8 -*-
"""


@author: Christian Talmadge
Convert CSV to GeoJSON
"""

import csv
import json
from collections import OrderedDict

li = []
#read csv
with open('nursingHomeData.csv', 'r') as csvfile:
    reader = csv.reader(csvfile, delimiter=',')
    #skip the first row(header)
    next(reader)
    for latitude, longitude, name, address, fine_amount, num_fines, scope  in reader:
        d = OrderedDict()
        #append to geojson format
        d['type'] = 'Feature'
        d['properties'] = {
            'name': name,
            'address': address,
            'fine_amount': float(fine_amount),
            'num_fines': float(num_fines),
            'scope': scope
            
        }
        d['geometry'] = {
            'type': 'Point',
            'coordinates': [float(longitude), float(latitude)]
        }
        li.append(d)

d = OrderedDict()
d['type'] = 'FeatureCollection'
d['features'] = li
#write to geojson file
with open('newestest1343431.geojson', 'w') as f:
    f.write(json.dumps(d, sort_keys=False, indent=4))