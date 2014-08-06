jQuery.validator.addMethod("notEqualToEmail", function(value, element, param) {
	return this.optional(element) || $("#"+param).val() != value;
}, "This has to be different...");

jQuery.validator.addMethod("notEqualToName", function(value, element, param) {
	return this.optional(element) || $("#"+param).val() != value;
}, "This has to be different...");
