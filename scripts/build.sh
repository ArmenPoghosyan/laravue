# Navigate to quasar directory
cd resources/js/quasar

# Build quasar
quasar build

# Navigate to root directory
cd ../../../

if [ -f "resources/js/quasar/dist/spa/index.html" ]; then
	# Move index.html as blade for Laravel
	mv -f resources/js/quasar/dist/spa/index.html resources/views/index.blade.php

	# If public/src directory does not exist
	if [ ! -d "public/src" ]; then
		# Create public/src directory
		mkdir public/src
	fi

	# Copy all built resources to public/src
	cp -rf resources/js/quasar/dist/spa/* public/src

	# Remove all built resources from quasar
	rm -rf resources/js/quasar/dist
fi
