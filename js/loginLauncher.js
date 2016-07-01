/*
 * Copyright © 2016 Valiton GmbH.
 *
 * This file is part of Harbourmaster Drupal Plugin.
 *
 * Harbourmaster Drupal Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Harbourmaster Drupal Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Harbourmaster Drupal Plugin.  If not, see <http://www.gnu.org/licenses/>.
 */

setNewTranslation = function(key, hash, drupaltranslation) {
  var firstKey, idx, remainingKeys;

  if (hash[key] != null) {
    hash[key] = drupaltranslation
    return hash[key];
  }

  if ((idx = key.indexOf('.')) !== -1) {
    firstKey = key.substr(0, idx);
    remainingKeys = key.substr(idx + 1);
    hash = hash[firstKey];
    if (hash) { return setNewTranslation(remainingKeys, hash, drupaltranslation); }
  }
};

var __usermanager_language = {
  
    "hms-widget.confirmation.title.confirmed": Drupal.t('hms-widget.confirmation.title.confirmed')
  
    ,"hms-widget.confirmation.title.invalidToken": Drupal.t('hms-widget.confirmation.title.invalidToken')
  
    ,"hms-widget.resetPassword.title.invalidToken": Drupal.t('hms-widget.resetPassword.title.invalidToken')
  
    ,"hms-widget.resetPassword.title.questions": Drupal.t('hms-widget.resetPassword.title.questions')
  
    ,"hms-widget.resetPassword.title.success": Drupal.t('hms-widget.resetPassword.title.success')
  
    ,"hms-widget.resetPassword.title.passwordreset": Drupal.t('hms-widget.resetPassword.title.passwordreset')
  
    ,"hms-widget.resetPassword.label.new_password": Drupal.t('hms-widget.resetPassword.label.new_password')
  
    ,"hms-widget.resetPassword.label.username": Drupal.t('hms-widget.resetPassword.label.username')
  
    ,"hms-widget.resetPassword.placeholder.new_password": Drupal.t('hms-widget.resetPassword.placeholder.new_password')
  
    ,"hms-widget.resetPassword.placeholder.validation_password": Drupal.t('hms-widget.resetPassword.placeholder.validation_password')
  
    ,"hms-widget.resetPassword.placeholder.username": Drupal.t('hms-widget.resetPassword.placeholder.username')
  
    ,"hms-widget.resetPassword.description.success_message": Drupal.t('hms-widget.resetPassword.description.success_message')
  
    ,"hms-widget.resetPassword.description.set_password": Drupal.t('hms-widget.resetPassword.description.set_password')
  
    ,"hms-widget.resetPassword.button.passwordreset": Drupal.t('hms-widget.resetPassword.button.passwordreset')
  
    ,"hms-widget.resetPassword.button.set_password": Drupal.t('hms-widget.resetPassword.button.set_password')
  
    ,"hms-widget.resetPassword.error.username.required": Drupal.t('hms-widget.resetPassword.error.username.required')
  
    ,"hms-widget.resetPassword.error.username.string": Drupal.t('hms-widget.resetPassword.error.username.string')
  
    ,"hms-widget.resetPassword.error.username.error": Drupal.t('hms-widget.resetPassword.error.username.error')
  
    ,"hms-widget.resetPassword.error.username.credentials": Drupal.t('hms-widget.resetPassword.error.username.credentials')
  
    ,"hms-widget.resetPassword.error.username.regex": Drupal.t('hms-widget.resetPassword.error.username.regex')
  
    ,"hms-widget.resetPassword.error.username.AccountHasNoEmailAddress": Drupal.t('hms-widget.resetPassword.error.username.AccountHasNoEmailAddress')
  
    ,"hms-widget.resetPassword.error.username.NoSuchUser": Drupal.t('hms-widget.resetPassword.error.username.NoSuchUser')
  
    ,"hms-widget.resetPassword.error.username.ConfirmationEmailSent": Drupal.t('hms-widget.resetPassword.error.username.ConfirmationEmailSent')
  
    ,"hms-widget.resetPassword.flash.success": Drupal.t('hms-widget.resetPassword.flash.success')
  
    ,"hms-widget.resetPassword.flash.error": Drupal.t('hms-widget.resetPassword.flash.error')
  
    ,"hms-widget.resetPassword.flash.passwordreset.error": Drupal.t('hms-widget.resetPassword.flash.passwordreset.error')
  
    ,"hms-widget.resetPassword.intro.text": Drupal.t('hms-widget.resetPassword.intro.text')
  
    ,"hms-widget.profile.title.profile": Drupal.t('hms-widget.profile.title.profile')
  
    ,"hms-widget.profile.title.member": Drupal.t('hms-widget.profile.title.member')
  
    ,"hms-widget.profile.title.password": Drupal.t('hms-widget.profile.title.password')
  
    ,"hms-widget.profile.title.securityQuestions": Drupal.t('hms-widget.profile.title.securityQuestions')
  
    ,"hms-widget.profile.title.memberAdvertisement": Drupal.t('hms-widget.profile.title.memberAdvertisement')
  
    ,"hms-widget.profile.label.day": Drupal.t('hms-widget.profile.label.day')
  
    ,"hms-widget.profile.label.month": Drupal.t('hms-widget.profile.label.month')
  
    ,"hms-widget.profile.label.year": Drupal.t('hms-widget.profile.label.year')
  
    ,"hms-widget.profile.label.address": Drupal.t('hms-widget.profile.label.address')
  
    ,"hms-widget.profile.label.oldPassword": Drupal.t('hms-widget.profile.label.oldPassword')
  
    ,"hms-widget.profile.label.password": Drupal.t('hms-widget.profile.label.password')
  
    ,"hms-widget.profile.label.protectProfile": Drupal.t('hms-widget.profile.label.protectProfile')
  
    ,"hms-widget.profile.label.avatar": Drupal.t('hms-widget.profile.label.avatar')
  
    ,"hms-widget.profile.label.avatarEdit": Drupal.t('hms-widget.profile.label.avatarEdit')
  
    ,"hms-widget.profile.label.displayName": Drupal.t('hms-widget.profile.label.displayName')
  
    ,"hms-widget.profile.label.name": Drupal.t('hms-widget.profile.label.name')
  
    ,"hms-widget.profile.label.email": Drupal.t('hms-widget.profile.label.email')
  
    ,"hms-widget.profile.label.securityQuestion": Drupal.t('hms-widget.profile.label.securityQuestion')
  
    ,"hms-widget.profile.label.answer": Drupal.t('hms-widget.profile.label.answer')
  
    ,"hms-widget.profile.label.community": Drupal.t('hms-widget.profile.label.community')
  
    ,"hms-widget.profile.instruction.avatar": Drupal.t('hms-widget.profile.instruction.avatar')
  
    ,"hms-widget.profile.instruction.displayName": Drupal.t('hms-widget.profile.instruction.displayName')
  
    ,"hms-widget.profile.placeholder.oldPassword": Drupal.t('hms-widget.profile.placeholder.oldPassword')
  
    ,"hms-widget.profile.placeholder.password": Drupal.t('hms-widget.profile.placeholder.password')
  
    ,"hms-widget.profile.placeholder.verifyPassword": Drupal.t('hms-widget.profile.placeholder.verifyPassword')
  
    ,"hms-widget.profile.placeholder.displayName": Drupal.t('hms-widget.profile.placeholder.displayName')
  
    ,"hms-widget.profile.placeholder.firstname": Drupal.t('hms-widget.profile.placeholder.firstname')
  
    ,"hms-widget.profile.placeholder.lastname": Drupal.t('hms-widget.profile.placeholder.lastname')
  
    ,"hms-widget.profile.placeholder.email_address": Drupal.t('hms-widget.profile.placeholder.email_address')
  
    ,"hms-widget.profile.placeholder.answer": Drupal.t('hms-widget.profile.placeholder.answer')
  
    ,"hms-widget.profile.placeholder.phone": Drupal.t('hms-widget.profile.placeholder.phone')
  
    ,"hms-widget.profile.placeholder.address.company": Drupal.t('hms-widget.profile.placeholder.address.company')
  
    ,"hms-widget.profile.placeholder.address.street": Drupal.t('hms-widget.profile.placeholder.address.street')
  
    ,"hms-widget.profile.placeholder.address.streetNumber": Drupal.t('hms-widget.profile.placeholder.address.streetNumber')
  
    ,"hms-widget.profile.placeholder.address.postcode": Drupal.t('hms-widget.profile.placeholder.address.postcode')
  
    ,"hms-widget.profile.placeholder.address.city": Drupal.t('hms-widget.profile.placeholder.address.city')
  
    ,"hms-widget.profile.placeholder.address.country": Drupal.t('hms-widget.profile.placeholder.address.country')
  
    ,"hms-widget.profile.select.salutation.mr": Drupal.t('hms-widget.profile.select.salutation.mr')
  
    ,"hms-widget.profile.select.salutation.mrs": Drupal.t('hms-widget.profile.select.salutation.mrs')
  
    ,"hms-widget.profile.select.salutation.none": Drupal.t('hms-widget.profile.select.salutation.none')
  
    ,"hms-widget.profile.checkbox.subscription.advertising": Drupal.t('hms-widget.profile.checkbox.subscription.advertising')
  
    ,"hms-widget.profile.checkbox.subscription.newsletter": Drupal.t('hms-widget.profile.checkbox.subscription.newsletter')
  
    ,"hms-widget.profile.tab.profile": Drupal.t('hms-widget.profile.tab.profile')
  
    ,"hms-widget.profile.tab.entitlements": Drupal.t('hms-widget.profile.tab.entitlements')
  
    ,"hms-widget.profile.button.upload": Drupal.t('hms-widget.profile.button.upload')
  
    ,"hms-widget.profile.button.change_password": Drupal.t('hms-widget.profile.button.change_password')
  
    ,"hms-widget.profile.button.security_question": Drupal.t('hms-widget.profile.button.security_question')
  
    ,"hms-widget.profile.button.memberAdvertisement": Drupal.t('hms-widget.profile.button.memberAdvertisement')
  
    ,"hms-widget.profile.button.post_entitlement": Drupal.t('hms-widget.profile.button.post_entitlement')
  
    ,"hms-widget.profile.button.entitelement_post_entitlement": Drupal.t('hms-widget.profile.button.entitelement_post_entitlement')
  
    ,"hms-widget.profile.error.displayName.string": Drupal.t('hms-widget.profile.error.displayName.string')
  
    ,"hms-widget.profile.error.displayName.maxLength": Drupal.t('hms-widget.profile.error.displayName.maxLength')
  
    ,"hms-widget.profile.error.displayName.minLength": Drupal.t('hms-widget.profile.error.displayName.minLength')
  
    ,"hms-widget.profile.error.displayName.DisplayNameAlreadyExists": Drupal.t('hms-widget.profile.error.displayName.DisplayNameAlreadyExists')
  
    ,"hms-widget.profile.error.displayName.DisplayNameIsRequired": Drupal.t('hms-widget.profile.error.displayName.DisplayNameIsRequired')
  
    ,"hms-widget.profile.error.displayName.regex": Drupal.t('hms-widget.profile.error.displayName.regex')
  
    ,"hms-widget.profile.error.displayName.required": Drupal.t('hms-widget.profile.error.displayName.required')
  
    ,"hms-widget.profile.error.email.string": Drupal.t('hms-widget.profile.error.email.string')
  
    ,"hms-widget.profile.error.email.regex": Drupal.t('hms-widget.profile.error.email.regex')
  
    ,"hms-widget.profile.error.email.fakeemail": Drupal.t('hms-widget.profile.error.email.fakeemail')
  
    ,"hms-widget.profile.error.email.required": Drupal.t('hms-widget.profile.error.email.required')
  
    ,"hms-widget.profile.error.email.LoginAlreadyExists": Drupal.t('hms-widget.profile.error.email.LoginAlreadyExists')
  
    ,"hms-widget.profile.error.email.EmailAlreadyExists": Drupal.t('hms-widget.profile.error.email.EmailAlreadyExists')
  
    ,"hms-widget.profile.error.email.ArgLoginIsRequired": Drupal.t('hms-widget.profile.error.email.ArgLoginIsRequired')
  
    ,"hms-widget.profile.error.email.LoginCannotBeEmpty": Drupal.t('hms-widget.profile.error.email.LoginCannotBeEmpty')
  
    ,"hms-widget.profile.error.oldPassword.string": Drupal.t('hms-widget.profile.error.oldPassword.string')
  
    ,"hms-widget.profile.error.oldPassword.required": Drupal.t('hms-widget.profile.error.oldPassword.required')
  
    ,"hms-widget.profile.error.oldPassword.IncorrectOldPassword": Drupal.t('hms-widget.profile.error.oldPassword.IncorrectOldPassword')
  
    ,"hms-widget.profile.error.password.string": Drupal.t('hms-widget.profile.error.password.string')
  
    ,"hms-widget.profile.error.password.minLength": Drupal.t('hms-widget.profile.error.password.minLength')
  
    ,"hms-widget.profile.error.password.regex": Drupal.t('hms-widget.profile.error.password.regex')
  
    ,"hms-widget.profile.error.password.required": Drupal.t('hms-widget.profile.error.password.required')
  
    ,"hms-widget.profile.error.password.ArgPasswordIsInvalid": Drupal.t('hms-widget.profile.error.password.ArgPasswordIsInvalid')
  
    ,"hms-widget.profile.error.password.NoNumbers": Drupal.t('hms-widget.profile.error.password.NoNumbers')
  
    ,"hms-widget.profile.error.password.NoLowerCaseLetters": Drupal.t('hms-widget.profile.error.password.NoLowerCaseLetters')
  
    ,"hms-widget.profile.error.password.NoUpperCaseLetters": Drupal.t('hms-widget.profile.error.password.NoUpperCaseLetters')
  
    ,"hms-widget.profile.error.password.NotMinEigth": Drupal.t('hms-widget.profile.error.password.NotMinEigth')
  
    ,"hms-widget.profile.error.verifyPassword.string": Drupal.t('hms-widget.profile.error.verifyPassword.string')
  
    ,"hms-widget.profile.error.verifyPassword.passwordEqual": Drupal.t('hms-widget.profile.error.verifyPassword.passwordEqual')
  
    ,"hms-widget.profile.error.verifyPassword.required": Drupal.t('hms-widget.profile.error.verifyPassword.required')
  
    ,"hms-widget.profile.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('hms-widget.profile.error.verifyPassword.PasswordsDoNotMatch')
  
    ,"hms-widget.profile.error.salutation.string": Drupal.t('hms-widget.profile.error.salutation.string')
  
    ,"hms-widget.profile.error.salutation.in": Drupal.t('hms-widget.profile.error.salutation.in')
  
    ,"hms-widget.profile.error.salutation.required": Drupal.t('hms-widget.profile.error.salutation.required')
  
    ,"hms-widget.profile.error.firstname.string": Drupal.t('hms-widget.profile.error.firstname.string')
  
    ,"hms-widget.profile.error.firstname.required": Drupal.t('hms-widget.profile.error.firstname.required')
  
    ,"hms-widget.profile.error.lastname.string": Drupal.t('hms-widget.profile.error.lastname.string')
  
    ,"hms-widget.profile.error.lastname.required": Drupal.t('hms-widget.profile.error.lastname.required')
  
    ,"hms-widget.profile.error.securityQuestions.string": Drupal.t('hms-widget.profile.error.securityQuestions.string')
  
    ,"hms-widget.profile.error.securityQuestions.required": Drupal.t('hms-widget.profile.error.securityQuestions.required')
  
    ,"hms-widget.profile.info.password": Drupal.t('hms-widget.profile.info.password')
  
    ,"hms-widget.profile.flash.success": Drupal.t('hms-widget.profile.flash.success')
  
    ,"hms-widget.profile.flash.error": Drupal.t('hms-widget.profile.flash.error')
  
    ,"hms-widget.signup.title.signup": Drupal.t('hms-widget.signup.title.signup')
  
    ,"hms-widget.signup.title.error": Drupal.t('hms-widget.signup.title.error')
  
    ,"hms-widget.signup.title.success": Drupal.t('hms-widget.signup.title.success')
  
    ,"hms-widget.signup.label.displayName": Drupal.t('hms-widget.signup.label.displayName')
  
    ,"hms-widget.signup.label.email": Drupal.t('hms-widget.signup.label.email')
  
    ,"hms-widget.signup.label.password": Drupal.t('hms-widget.signup.label.password')
  
    ,"hms-widget.signup.label.name": Drupal.t('hms-widget.signup.label.name')
  
    ,"hms-widget.signup.label.error": Drupal.t('hms-widget.signup.label.error')
  
    ,"hms-widget.signup.label.firstname": Drupal.t('hms-widget.signup.label.firstname')
  
    ,"hms-widget.signup.label.lastname": Drupal.t('hms-widget.signup.label.lastname')
  
    ,"hms-widget.signup.placeholder.displayName": Drupal.t('hms-widget.signup.placeholder.displayName')
  
    ,"hms-widget.signup.placeholder.email": Drupal.t('hms-widget.signup.placeholder.email')
  
    ,"hms-widget.signup.placeholder.password": Drupal.t('hms-widget.signup.placeholder.password')
  
    ,"hms-widget.signup.placeholder.verifyPassword": Drupal.t('hms-widget.signup.placeholder.verifyPassword')
  
    ,"hms-widget.signup.placeholder.firstname": Drupal.t('hms-widget.signup.placeholder.firstname')
  
    ,"hms-widget.signup.placeholder.lastname": Drupal.t('hms-widget.signup.placeholder.lastname')
  
    ,"hms-widget.signup.select.salutation.none": Drupal.t('hms-widget.signup.select.salutation.none')
  
    ,"hms-widget.signup.select.salutation.mr": Drupal.t('hms-widget.signup.select.salutation.mr')
  
    ,"hms-widget.signup.select.salutation.mrs": Drupal.t('hms-widget.signup.select.salutation.mrs')
  
    ,"hms-widget.signup.checkbox.terms_and_policy": Drupal.t('hms-widget.signup.checkbox.terms_and_policy')
  
    ,"hms-widget.signup.checkbox.subscription.advertising": Drupal.t('hms-widget.signup.checkbox.subscription.advertising')
  
    ,"hms-widget.signup.checkbox.subscription.newsletter": Drupal.t('hms-widget.signup.checkbox.subscription.newsletter')
  
    ,"hms-widget.signup.button.signup": Drupal.t('hms-widget.signup.button.signup')
  
    ,"hms-widget.signup.button.signin": Drupal.t('hms-widget.signup.button.signin')
  
    ,"hms-widget.signup.error.displayName.string": Drupal.t('hms-widget.signup.error.displayName.string')
  
    ,"hms-widget.signup.error.displayName.maxLength": Drupal.t('hms-widget.signup.error.displayName.maxLength')
  
    ,"hms-widget.signup.error.displayName.minLength": Drupal.t('hms-widget.signup.error.displayName.minLength')
  
    ,"hms-widget.signup.error.displayName.required": Drupal.t('hms-widget.signup.error.displayName.required')
  
    ,"hms-widget.signup.error.displayName.LoginAlreadyExists": Drupal.t('hms-widget.signup.error.displayName.LoginAlreadyExists')
  
    ,"hms-widget.signup.error.displayName.DisplayNameAlreadyExists": Drupal.t('hms-widget.signup.error.displayName.DisplayNameAlreadyExists')
  
    ,"hms-widget.signup.error.displayName.DisplayNameIsRequired": Drupal.t('hms-widget.signup.error.displayName.DisplayNameIsRequired')
  
    ,"hms-widget.signup.error.displayName.regex": Drupal.t('hms-widget.signup.error.displayName.regex')
  
    ,"hms-widget.signup.error.email.string": Drupal.t('hms-widget.signup.error.email.string')
  
    ,"hms-widget.signup.error.email.regex": Drupal.t('hms-widget.signup.error.email.regex')
  
    ,"hms-widget.signup.error.email.fakeemail": Drupal.t('hms-widget.signup.error.email.fakeemail')
  
    ,"hms-widget.signup.error.email.required": Drupal.t('hms-widget.signup.error.email.required')
  
    ,"hms-widget.signup.error.email.LoginAlreadyExists": Drupal.t('hms-widget.signup.error.email.LoginAlreadyExists')
  
    ,"hms-widget.signup.error.email.EmailAlreadyExists": Drupal.t('hms-widget.signup.error.email.EmailAlreadyExists')
  
    ,"hms-widget.signup.error.email.ArgLoginIsRequired": Drupal.t('hms-widget.signup.error.email.ArgLoginIsRequired')
  
    ,"hms-widget.signup.error.email.LoginCannotBeEmpty": Drupal.t('hms-widget.signup.error.email.LoginCannotBeEmpty')
  
    ,"hms-widget.signup.error.password.string": Drupal.t('hms-widget.signup.error.password.string')
  
    ,"hms-widget.signup.error.password.minLength": Drupal.t('hms-widget.signup.error.password.minLength')
  
    ,"hms-widget.signup.error.password.regex": Drupal.t('hms-widget.signup.error.password.regex')
  
    ,"hms-widget.signup.error.password.required": Drupal.t('hms-widget.signup.error.password.required')
  
    ,"hms-widget.signup.error.password.ArgPasswordIsInvalid": Drupal.t('hms-widget.signup.error.password.ArgPasswordIsInvalid')
  
    ,"hms-widget.signup.error.password.NoNumbers": Drupal.t('hms-widget.signup.error.password.NoNumbers')
  
    ,"hms-widget.signup.error.password.NoLowerCaseLetters": Drupal.t('hms-widget.signup.error.password.NoLowerCaseLetters')
  
    ,"hms-widget.signup.error.password.NoUpperCaseLetters": Drupal.t('hms-widget.signup.error.password.NoUpperCaseLetters')
  
    ,"hms-widget.signup.error.password.NotMinEigth": Drupal.t('hms-widget.signup.error.password.NotMinEigth')
  
    ,"hms-widget.signup.error.verifyPassword.string": Drupal.t('hms-widget.signup.error.verifyPassword.string')
  
    ,"hms-widget.signup.error.verifyPassword.passwordEqual": Drupal.t('hms-widget.signup.error.verifyPassword.passwordEqual')
  
    ,"hms-widget.signup.error.verifyPassword.required": Drupal.t('hms-widget.signup.error.verifyPassword.required')
  
    ,"hms-widget.signup.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('hms-widget.signup.error.verifyPassword.PasswordsDoNotMatch')
  
    ,"hms-widget.signup.error.salutation.string": Drupal.t('hms-widget.signup.error.salutation.string')
  
    ,"hms-widget.signup.error.salutation.in": Drupal.t('hms-widget.signup.error.salutation.in')
  
    ,"hms-widget.signup.error.salutation.required": Drupal.t('hms-widget.signup.error.salutation.required')
  
    ,"hms-widget.signup.error.firstname.string": Drupal.t('hms-widget.signup.error.firstname.string')
  
    ,"hms-widget.signup.error.firstname.required": Drupal.t('hms-widget.signup.error.firstname.required')
  
    ,"hms-widget.signup.error.lastname.string": Drupal.t('hms-widget.signup.error.lastname.string')
  
    ,"hms-widget.signup.error.lastname.required": Drupal.t('hms-widget.signup.error.lastname.required')
  
    ,"hms-widget.signup.error.terms.boolean": Drupal.t('hms-widget.signup.error.terms.boolean')
  
    ,"hms-widget.signup.error.terms.truthy": Drupal.t('hms-widget.signup.error.terms.truthy')
  
    ,"hms-widget.signup.intro.teaser": Drupal.t('hms-widget.signup.intro.teaser')
  
    ,"hms-widget.signup.intro.text": Drupal.t('hms-widget.signup.intro.text')
  
    ,"hms-widget.signup.info.password": Drupal.t('hms-widget.signup.info.password')
  
    ,"hms-widget.signup.flash.error": Drupal.t('hms-widget.signup.flash.error')
  
    ,"hms-widget.signup.flash.success": Drupal.t('hms-widget.signup.flash.success')
  
    ,"hms-widget.signin.title.signin": Drupal.t('hms-widget.signin.title.signin')
  
    ,"hms-widget.signin.title.success": Drupal.t('hms-widget.signin.title.success')
  
    ,"hms-widget.signin.title.passwordreset": Drupal.t('hms-widget.signin.title.passwordreset')
  
    ,"hms-widget.signin.title.passwordresetinit": Drupal.t('hms-widget.signin.title.passwordresetinit')
  
    ,"hms-widget.signin.title.unconfirmed": Drupal.t('hms-widget.signin.title.unconfirmed')
  
    ,"hms-widget.signin.label.username": Drupal.t('hms-widget.signin.label.username')
  
    ,"hms-widget.signin.label.password": Drupal.t('hms-widget.signin.label.password')
  
    ,"hms-widget.signin.placeholder.username": Drupal.t('hms-widget.signin.placeholder.username')
  
    ,"hms-widget.signin.placeholder.password": Drupal.t('hms-widget.signin.placeholder.password')
  
    ,"hms-widget.signin.button.signin": Drupal.t('hms-widget.signin.button.signin')
  
    ,"hms-widget.signin.button.signup": Drupal.t('hms-widget.signin.button.signup')
  
    ,"hms-widget.signin.button.passwordreset": Drupal.t('hms-widget.signin.button.passwordreset')
  
    ,"hms-widget.signin.button.unconfirmed": Drupal.t('hms-widget.signin.button.unconfirmed')
  
    ,"hms-widget.signin.error.username.required": Drupal.t('hms-widget.signin.error.username.required')
  
    ,"hms-widget.signin.error.username.string": Drupal.t('hms-widget.signin.error.username.string')
  
    ,"hms-widget.signin.error.username.error": Drupal.t('hms-widget.signin.error.username.error')
  
    ,"hms-widget.signin.error.username.credentials": Drupal.t('hms-widget.signin.error.username.credentials')
  
    ,"hms-widget.signin.error.username.regex": Drupal.t('hms-widget.signin.error.username.regex')
  
    ,"hms-widget.signin.error.username.AccountHasNoEmailAddress": Drupal.t('hms-widget.signin.error.username.AccountHasNoEmailAddress')
  
    ,"hms-widget.signin.error.username.NoSuchUser": Drupal.t('hms-widget.signin.error.username.NoSuchUser')
  
    ,"hms-widget.signin.error.username.UserNotConfirmed": Drupal.t('hms-widget.signin.error.username.UserNotConfirmed')
  
    ,"hms-widget.signin.error.password.required": Drupal.t('hms-widget.signin.error.password.required')
  
    ,"hms-widget.signin.error.password.error": Drupal.t('hms-widget.signin.error.password.error')
  
    ,"hms-widget.signin.error.password.credentials": Drupal.t('hms-widget.signin.error.password.credentials')
  
    ,"hms-widget.signin.error.requesttoken.LoginIsRequired": Drupal.t('hms-widget.signin.error.requesttoken.LoginIsRequired')
  
    ,"hms-widget.signin.error.requesttoken.AccountHasNoEmailAddress": Drupal.t('hms-widget.signin.error.requesttoken.AccountHasNoEmailAddress')
  
    ,"hms-widget.signin.error.requesttoken.NoSuchUser": Drupal.t('hms-widget.signin.error.requesttoken.NoSuchUser')
  
    ,"hms-widget.signin.message.unconfirmed": Drupal.t('hms-widget.signin.message.unconfirmed')
  
    ,"hms-widget.signin.message.unconfirmedresend": Drupal.t('hms-widget.signin.message.unconfirmedresend')
  
    ,"hms-widget.signin.message.passwordreset.email_sent": Drupal.t('hms-widget.signin.message.passwordreset.email_sent')
  
    ,"hms-widget.signin.link.passwordreset": Drupal.t('hms-widget.signin.link.passwordreset')
  
    ,"hms-widget.signin.link.activate": Drupal.t('hms-widget.signin.link.activate')
  
    ,"hms-widget.signin.tooltip.twitter": Drupal.t('hms-widget.signin.tooltip.twitter')
  
    ,"hms-widget.signin.tooltip.facebook": Drupal.t('hms-widget.signin.tooltip.facebook')
  
    ,"hms-widget.signin.tooltip.google": Drupal.t('hms-widget.signin.tooltip.google')
  
    ,"hms-widget.signin.tooltip.linkedin": Drupal.t('hms-widget.signin.tooltip.linkedin')
  
    ,"hms-widget.signin.tooltip.github": Drupal.t('hms-widget.signin.tooltip.github')
  
    ,"hms-widget.signin.flash.passwordreset.error": Drupal.t('hms-widget.signin.flash.passwordreset.error')
  
    ,"hms-widget.signin.flash.error": Drupal.t('hms-widget.signin.flash.error')
  
    ,"hms-widget.signin.alternate_login_methods": Drupal.t('hms-widget.signin.alternate_login_methods')
  
    ,"hms-widget.password.title.invalidToken": Drupal.t('hms-widget.password.title.invalidToken')
  
    ,"hms-widget.password.title.reset": Drupal.t('hms-widget.password.title.reset')
  
    ,"hms-widget.password.title.securityQuestions": Drupal.t('hms-widget.password.title.securityQuestions')
  
    ,"hms-widget.password.title.success": Drupal.t('hms-widget.password.title.success')
  
    ,"hms-widget.password.label.password": Drupal.t('hms-widget.password.label.password')
  
    ,"hms-widget.password.placeholder.answer": Drupal.t('hms-widget.password.placeholder.answer')
  
    ,"hms-widget.password.placeholder.password": Drupal.t('hms-widget.password.placeholder.password')
  
    ,"hms-widget.password.placeholder.verifyPassword": Drupal.t('hms-widget.password.placeholder.verifyPassword')
  
    ,"hms-widget.password.button.changePassword": Drupal.t('hms-widget.password.button.changePassword')
  
    ,"hms-widget.password.error.password.string": Drupal.t('hms-widget.password.error.password.string')
  
    ,"hms-widget.password.error.password.minLength": Drupal.t('hms-widget.password.error.password.minLength')
  
    ,"hms-widget.password.error.password.regex": Drupal.t('hms-widget.password.error.password.regex')
  
    ,"hms-widget.password.error.password.required": Drupal.t('hms-widget.password.error.password.required')
  
    ,"hms-widget.password.error.password.ArgPasswordIsInvalid": Drupal.t('hms-widget.password.error.password.ArgPasswordIsInvalid')
  
    ,"hms-widget.password.error.password.NewPasswordRequired": Drupal.t('hms-widget.password.error.password.NewPasswordRequired')
  
    ,"hms-widget.password.error.password.NoNumbers": Drupal.t('hms-widget.password.error.password.NoNumbers')
  
    ,"hms-widget.password.error.password.NoLowerCaseLetters": Drupal.t('hms-widget.password.error.password.NoLowerCaseLetters')
  
    ,"hms-widget.password.error.password.NoUpperCaseLetters": Drupal.t('hms-widget.password.error.password.NoUpperCaseLetters')
  
    ,"hms-widget.password.error.password.NotMinEigth": Drupal.t('hms-widget.password.error.password.NotMinEigth')
  
    ,"hms-widget.password.error.verifyPassword.string": Drupal.t('hms-widget.password.error.verifyPassword.string')
  
    ,"hms-widget.password.error.verifyPassword.passwordEqual": Drupal.t('hms-widget.password.error.verifyPassword.passwordEqual')
  
    ,"hms-widget.password.error.verifyPassword.required": Drupal.t('hms-widget.password.error.verifyPassword.required')
  
    ,"hms-widget.password.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('hms-widget.password.error.verifyPassword.PasswordsDoNotMatch')
  
    ,"hms-widget.password.error.answer.string": Drupal.t('hms-widget.password.error.answer.string')
  
    ,"hms-widget.password.error.answer.required": Drupal.t('hms-widget.password.error.answer.required')
  
    ,"hms-widget.password.error.answer.CannotParseSecurityQuestions": Drupal.t('hms-widget.password.error.answer.CannotParseSecurityQuestions')
  
    ,"hms-widget.password.error.answer.IncompleteSecurityQuestions": Drupal.t('hms-widget.password.error.answer.IncompleteSecurityQuestions')
  
    ,"hms-widget.password.error.answer.WrongSecurityQuestionsAnswer": Drupal.t('hms-widget.password.error.answer.WrongSecurityQuestionsAnswer')
  
    ,"hms-widget.password.error.token.PasswordRecoveryTokenRequired": Drupal.t('hms-widget.password.error.token.PasswordRecoveryTokenRequired')
  
    ,"hms-widget.password.flash.error": Drupal.t('hms-widget.password.flash.error')
  
    ,"hms-widget.password.intro.text": Drupal.t('hms-widget.password.intro.text')
  
    ,"hms-widget.general.button.save": Drupal.t('hms-widget.general.button.save')
  
    ,"hms-widget.general.button.close": Drupal.t('hms-widget.general.button.close')
  
    ,"hms-widget.general.button.back": Drupal.t('hms-widget.general.button.back')
  
    ,"hms-widget.general.forbidden": Drupal.t('hms-widget.general.forbidden')
  
    ,"hms-widget.general.error": Drupal.t('hms-widget.general.error')
  
};



for(var key in __usermanager_language) {
  var value = __usermanager_language[key]
  if(key != value) { // only overwrite  Ember.I18n.translations when drupal give a translation
    var ref;
    if (typeof Ember !== "undefined" && Ember !== null ? (ref = Ember.I18n) != null ? ref.translations : void 0 : void 0) {

      unprefixedKey = key.replace("hms-widget.","");
      setNewTranslation(unprefixedKey, Ember.I18n.translations, value);
    }
  }
}

