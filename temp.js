
  function initPlaces() {
    const addressInput = document.getElementById('cust_address');
    if (addressInput && window.google) {
      const autocomplete = new google.maps.places.Autocomplete(addressInput, {
        componentRestrictions: { country: 'us' },
        fields: ['formatted_address', 'name'],
      });
      // Prevent the enter key from submitting the form when selecting an address
      addressInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
        }
      });
    }
  }
