#!/usr/bin/python 
# Python scripts for automatic live results uploading.
# Lucas Garron
# (Based on code by Dan Cohen)
#
# Doesn't work in Python 3.

import ClientForm, base64, urllib2, time, os, sys

livefile = os.path.dirname(sys.argv[0])+"/CompetitionSpreadsheet.xls"
sleeplength = 300
uploadurl = "http://www.example.com/path/to/results/admin.php"
uploadpass = "whootchaasdf123!"
initialwait = 1

print "-----------------------------------------"
print "-Welcome to the automatic live results uploader v1.3"
print "-Results will be synched every "+str(sleeplength)+" seconds (upload will take up to 10-20 seconds if the spreadsheet has changed)."
print "-Make sure to save the spreadsheet frequently to ensure synching."
print "-Note: A backup is saved on the server every upload."
print "-Synching will begin in "+str(initialwait)+" seconds."
print "-----------------------------------------"

time.sleep(initialwait)

prev=""
while 1:
	f=open(livefile,"rb").read()
	if prev!=f:
		print time.asctime()+" - Uploading..."
		prev=f
		start = time.time()
		request = urllib2.Request(uploadurl)
		response = urllib2.urlopen(request)
		forms = ClientForm.ParseResponse(response, backwards_compat=False)
		response.close()
		form = forms[0]
		#print form  # very useful!
		
		form.add_file(open(livefile), "application/vnd.ms-excel", livefile)
		form["pass"] = uploadpass
		
		request2 = form.click()  # urllib2.Request object
		try:
		    response2 = urllib2.urlopen(request2)
		except urllib2.HTTPError, response2:
		    pass
		
		#print response2.geturl()
		#print response2.info()  # headers

		#print oeoe

		responseText = response2.read()
		textResponseStart = responseText.find("<textresponse>")
		textResponseEnd = responseText.find("</textresponse>")
                
		print responseText[textResponseStart+14:textResponseEnd]  # body
		response2.close()
		elapsed = (time.time() - start)
		print "Took "+str(elapsed)+" seconds."
	else:
		print time.asctime()+" - Unchanged."
	time.sleep(sleeplength)	
