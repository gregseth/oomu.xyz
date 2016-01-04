#! /usr/bin/env python
# -*- coding: utf-8 -*-

import os
import fnmatch
import re

def returnold(folder):
    matches = []
    for root, dirnames, filenames in os.walk(folder, followlinks=True):
    	# print 'r='+root
        for filename in fnmatch.filter(filenames, '*.*'):
            # print 'f='+filename
            matches.append(os.path.join(root, filename))
    return sorted(matches, key=os.path.getmtime, reverse=True)

exclude = [
    '@eaDir',
    'Resources','Lightroom','Buddy Icons','Bibliothèque ',
    'Caps','Decks',
    'psd$','gif$','pdf$','txt$','mp4$','xpm$','gz$'
    ]
filter = re.compile('|'.join(exclude), re.UNICODE)
images = returnold('img')

print "\n".join([i for i in images if not filter.search(i)])
