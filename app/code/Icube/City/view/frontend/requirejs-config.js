var config = {
    map: {
        "*" : {
            "citykecamatanCustomer": "Icube_City/js/citykecamatancustomer",
            "separatorAddresscustomer": "Icube_City/js/jquery.icube.separator.customeraddress"
        }
    },
    deps: [
        "Magento_Checkout/js/checkout-loader"
    ],
    shim: {
	    "citykecamatanCustomer" : {
			deps: ["jquery"]
		},
		"separatorAddresscustomer" : {
			deps: ["jquery"]
		}
    }
}