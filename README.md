ce_kraken
=========

An Expression Engine add-on crushing images saved by CE Image using the Kraken.io API

# Requirements

This add-on is an extension for Expression Engine and requires you to have CE Image (http://www.causingeffect.com/software/expressionengine/ce-image) installed and an active API account for Kraken (https://kraken.io/).


# Usage

This EE extension is provided for free under the MIT license so feel free to use as you please (more or less). We will not be providing any support for this extension but if you leave a message or drop an email then we will try to respond.


# What

This extension will use the hook provided by CE Image 'ce\_img\_saved' which is triggered when a new image is resized for front end use. This triggers a script which will send the sized image to the Kraken API where it will work its magic and return a crushed image which will then replace the file created by CE Image.


# Why

We built this extension to compress image file sizes on the fly in expression to improve page performance and load times, especially for mobile devices operating over 3G etc.


# How

This is a basic extension as most of the hard work has ben done by CE Image and Kraken. You simply need to upload the extension to your third_party folder, enable it and configure a few settings;

## Kraken API Key:
This is available from the 'my account' area when logged in to Kraken.

## Kraken API Secret:
As above.

## Path to Made Folder:
This can be a little more tricky, CE Image provides, as part of the hook, the full server path to the image file, so we need to convert this to an accessible URL for Kraken.
Enter the full server path stopping at a level above the made folder but including a trailing slash, e.g /var/www/mysite/pub/images/.

## URL to Made Folder:
Important to get this right again, this will provide the url equivalent of the above, remember to take in to account any re-writes etc you may have in place e.g http://www.mysite.com/images/ (as our root is pointing to the pub folder already).


# Notes

The back end of the extension will allow you to test your API credentials but not the paths provided to the 'made' folder. The extension runs in the background so that it will not affect any front end rendering and as such if there is an error in posting or receiving data then there will be no indication of this to the end user.

To test we recommend that you navigate to a page where you know there is an image link to the 'made' folder, navigate to that image over ftp, note the size of the image and then delete it. Refreshing the web page will then trigger CE Image to re-size the image* and in turn trigger the extension. Refresh your ftp view and if everything is working correctly then the image should be back in the folder and with a smaller size** than previous.

*In some cases with heavy template caching if the static file is served it will assume that the image still exists and you may see broken image tags on the page. To resolve this you will need to refresh the caches for these pages.

**Not every image can be crushed by Kraken, see their docs for more information.