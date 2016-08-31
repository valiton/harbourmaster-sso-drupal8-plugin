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

    "harbourmaster.widget.confirmation.title.confirmed": Drupal.t('harbourmaster.widget.confirmation.title.confirmed')

    ,"harbourmaster.widget.confirmation.title.invalidToken": Drupal.t('harbourmaster.widget.confirmation.title.invalidToken')

    ,"harbourmaster.widget.resetPassword.title.invalidToken": Drupal.t('harbourmaster.widget.resetPassword.title.invalidToken')

    ,"harbourmaster.widget.resetPassword.title.questions": Drupal.t('harbourmaster.widget.resetPassword.title.questions')

    ,"harbourmaster.widget.resetPassword.title.success": Drupal.t('harbourmaster.widget.resetPassword.title.success')

    ,"harbourmaster.widget.resetPassword.title.passwordreset": Drupal.t('harbourmaster.widget.resetPassword.title.passwordreset')

    ,"harbourmaster.widget.resetPassword.label.new_password": Drupal.t('harbourmaster.widget.resetPassword.label.new_password')

    ,"harbourmaster.widget.resetPassword.label.username": Drupal.t('harbourmaster.widget.resetPassword.label.username')

    ,"harbourmaster.widget.resetPassword.placeholder.new_password": Drupal.t('harbourmaster.widget.resetPassword.placeholder.new_password')

    ,"harbourmaster.widget.resetPassword.placeholder.validation_password": Drupal.t('harbourmaster.widget.resetPassword.placeholder.validation_password')

    ,"harbourmaster.widget.resetPassword.placeholder.username": Drupal.t('harbourmaster.widget.resetPassword.placeholder.username')

    ,"harbourmaster.widget.resetPassword.description.success_message": Drupal.t('harbourmaster.widget.resetPassword.description.success_message')

    ,"harbourmaster.widget.resetPassword.description.set_password": Drupal.t('harbourmaster.widget.resetPassword.description.set_password')

    ,"harbourmaster.widget.resetPassword.button.passwordreset": Drupal.t('harbourmaster.widget.resetPassword.button.passwordreset')

    ,"harbourmaster.widget.resetPassword.button.set_password": Drupal.t('harbourmaster.widget.resetPassword.button.set_password')

    ,"harbourmaster.widget.resetPassword.error.username.required": Drupal.t('harbourmaster.widget.resetPassword.error.username.required')

    ,"harbourmaster.widget.resetPassword.error.username.string": Drupal.t('harbourmaster.widget.resetPassword.error.username.string')

    ,"harbourmaster.widget.resetPassword.error.username.error": Drupal.t('harbourmaster.widget.resetPassword.error.username.error')

    ,"harbourmaster.widget.resetPassword.error.username.credentials": Drupal.t('harbourmaster.widget.resetPassword.error.username.credentials')

    ,"harbourmaster.widget.resetPassword.error.username.regex": Drupal.t('harbourmaster.widget.resetPassword.error.username.regex')

    ,"harbourmaster.widget.resetPassword.error.username.AccountHasNoEmailAddress": Drupal.t('harbourmaster.widget.resetPassword.error.username.AccountHasNoEmailAddress')

    ,"harbourmaster.widget.resetPassword.error.username.NoSuchUser": Drupal.t('harbourmaster.widget.resetPassword.error.username.NoSuchUser')

    ,"harbourmaster.widget.resetPassword.error.username.ConfirmationEmailSent": Drupal.t('harbourmaster.widget.resetPassword.error.username.ConfirmationEmailSent')

    ,"harbourmaster.widget.resetPassword.flash.success": Drupal.t('harbourmaster.widget.resetPassword.flash.success')

    ,"harbourmaster.widget.resetPassword.flash.error": Drupal.t('harbourmaster.widget.resetPassword.flash.error')

    ,"harbourmaster.widget.resetPassword.flash.passwordreset.error": Drupal.t('harbourmaster.widget.resetPassword.flash.passwordreset.error')

    ,"harbourmaster.widget.resetPassword.intro.text": Drupal.t('harbourmaster.widget.resetPassword.intro.text')

    ,"harbourmaster.widget.profile.title.profile": Drupal.t('harbourmaster.widget.profile.title.profile')

    ,"harbourmaster.widget.profile.title.member": Drupal.t('harbourmaster.widget.profile.title.member')

    ,"harbourmaster.widget.profile.title.password": Drupal.t('harbourmaster.widget.profile.title.password')

    ,"harbourmaster.widget.profile.title.securityQuestions": Drupal.t('harbourmaster.widget.profile.title.securityQuestions')

    ,"harbourmaster.widget.profile.title.memberAdvertisement": Drupal.t('harbourmaster.widget.profile.title.memberAdvertisement')

    ,"harbourmaster.widget.profile.label.day": Drupal.t('harbourmaster.widget.profile.label.day')

    ,"harbourmaster.widget.profile.label.month": Drupal.t('harbourmaster.widget.profile.label.month')

    ,"harbourmaster.widget.profile.label.year": Drupal.t('harbourmaster.widget.profile.label.year')

    ,"harbourmaster.widget.profile.label.address": Drupal.t('harbourmaster.widget.profile.label.address')

    ,"harbourmaster.widget.profile.label.oldPassword": Drupal.t('harbourmaster.widget.profile.label.oldPassword')

    ,"harbourmaster.widget.profile.label.password": Drupal.t('harbourmaster.widget.profile.label.password')

    ,"harbourmaster.widget.profile.label.protectProfile": Drupal.t('harbourmaster.widget.profile.label.protectProfile')

    ,"harbourmaster.widget.profile.label.avatar": Drupal.t('harbourmaster.widget.profile.label.avatar')

    ,"harbourmaster.widget.profile.label.avatarEdit": Drupal.t('harbourmaster.widget.profile.label.avatarEdit')

    ,"harbourmaster.widget.profile.label.displayName": Drupal.t('harbourmaster.widget.profile.label.displayName')

    ,"harbourmaster.widget.profile.label.name": Drupal.t('harbourmaster.widget.profile.label.name')

    ,"harbourmaster.widget.profile.label.email": Drupal.t('harbourmaster.widget.profile.label.email')

    ,"harbourmaster.widget.profile.label.securityQuestion": Drupal.t('harbourmaster.widget.profile.label.securityQuestion')

    ,"harbourmaster.widget.profile.label.answer": Drupal.t('harbourmaster.widget.profile.label.answer')

    ,"harbourmaster.widget.profile.label.community": Drupal.t('harbourmaster.widget.profile.label.community')

    ,"harbourmaster.widget.profile.instruction.avatar": Drupal.t('harbourmaster.widget.profile.instruction.avatar')

    ,"harbourmaster.widget.profile.instruction.displayName": Drupal.t('harbourmaster.widget.profile.instruction.displayName')

    ,"harbourmaster.widget.profile.placeholder.oldPassword": Drupal.t('harbourmaster.widget.profile.placeholder.oldPassword')

    ,"harbourmaster.widget.profile.placeholder.password": Drupal.t('harbourmaster.widget.profile.placeholder.password')

    ,"harbourmaster.widget.profile.placeholder.verifyPassword": Drupal.t('harbourmaster.widget.profile.placeholder.verifyPassword')

    ,"harbourmaster.widget.profile.placeholder.displayName": Drupal.t('harbourmaster.widget.profile.placeholder.displayName')

    ,"harbourmaster.widget.profile.placeholder.firstname": Drupal.t('harbourmaster.widget.profile.placeholder.firstname')

    ,"harbourmaster.widget.profile.placeholder.lastname": Drupal.t('harbourmaster.widget.profile.placeholder.lastname')

    ,"harbourmaster.widget.profile.placeholder.email_address": Drupal.t('harbourmaster.widget.profile.placeholder.email_address')

    ,"harbourmaster.widget.profile.placeholder.answer": Drupal.t('harbourmaster.widget.profile.placeholder.answer')

    ,"harbourmaster.widget.profile.placeholder.phone": Drupal.t('harbourmaster.widget.profile.placeholder.phone')

    ,"harbourmaster.widget.profile.placeholder.address.company": Drupal.t('harbourmaster.widget.profile.placeholder.address.company')

    ,"harbourmaster.widget.profile.placeholder.address.street": Drupal.t('harbourmaster.widget.profile.placeholder.address.street')

    ,"harbourmaster.widget.profile.placeholder.address.streetNumber": Drupal.t('harbourmaster.widget.profile.placeholder.address.streetNumber')

    ,"harbourmaster.widget.profile.placeholder.address.postcode": Drupal.t('harbourmaster.widget.profile.placeholder.address.postcode')

    ,"harbourmaster.widget.profile.placeholder.address.city": Drupal.t('harbourmaster.widget.profile.placeholder.address.city')

    ,"harbourmaster.widget.profile.placeholder.address.country": Drupal.t('harbourmaster.widget.profile.placeholder.address.country')

    ,"harbourmaster.widget.profile.select.salutation.mr": Drupal.t('harbourmaster.widget.profile.select.salutation.mr')

    ,"harbourmaster.widget.profile.select.salutation.mrs": Drupal.t('harbourmaster.widget.profile.select.salutation.mrs')

    ,"harbourmaster.widget.profile.select.salutation.none": Drupal.t('harbourmaster.widget.profile.select.salutation.none')

    ,"harbourmaster.widget.profile.checkbox.subscription.advertising": Drupal.t('harbourmaster.widget.profile.checkbox.subscription.advertising')

    ,"harbourmaster.widget.profile.checkbox.subscription.newsletter": Drupal.t('harbourmaster.widget.profile.checkbox.subscription.newsletter')

    ,"harbourmaster.widget.profile.tab.profile": Drupal.t('harbourmaster.widget.profile.tab.profile')

    ,"harbourmaster.widget.profile.tab.entitlements": Drupal.t('harbourmaster.widget.profile.tab.entitlements')

    ,"harbourmaster.widget.profile.button.upload": Drupal.t('harbourmaster.widget.profile.button.upload')

    ,"harbourmaster.widget.profile.button.change_password": Drupal.t('harbourmaster.widget.profile.button.change_password')

    ,"harbourmaster.widget.profile.button.security_question": Drupal.t('harbourmaster.widget.profile.button.security_question')

    ,"harbourmaster.widget.profile.button.memberAdvertisement": Drupal.t('harbourmaster.widget.profile.button.memberAdvertisement')

    ,"harbourmaster.widget.profile.button.post_entitlement": Drupal.t('harbourmaster.widget.profile.button.post_entitlement')

    ,"harbourmaster.widget.profile.button.entitelement_post_entitlement": Drupal.t('harbourmaster.widget.profile.button.entitelement_post_entitlement')

    ,"harbourmaster.widget.profile.error.displayName.string": Drupal.t('harbourmaster.widget.profile.error.displayName.string')

    ,"harbourmaster.widget.profile.error.displayName.maxLength": Drupal.t('harbourmaster.widget.profile.error.displayName.maxLength')

    ,"harbourmaster.widget.profile.error.displayName.minLength": Drupal.t('harbourmaster.widget.profile.error.displayName.minLength')

    ,"harbourmaster.widget.profile.error.displayName.DisplayNameAlreadyExists": Drupal.t('harbourmaster.widget.profile.error.displayName.DisplayNameAlreadyExists')

    ,"harbourmaster.widget.profile.error.displayName.DisplayNameIsRequired": Drupal.t('harbourmaster.widget.profile.error.displayName.DisplayNameIsRequired')

    ,"harbourmaster.widget.profile.error.displayName.regex": Drupal.t('harbourmaster.widget.profile.error.displayName.regex')

    ,"harbourmaster.widget.profile.error.displayName.required": Drupal.t('harbourmaster.widget.profile.error.displayName.required')

    ,"harbourmaster.widget.profile.error.email.string": Drupal.t('harbourmaster.widget.profile.error.email.string')

    ,"harbourmaster.widget.profile.error.email.regex": Drupal.t('harbourmaster.widget.profile.error.email.regex')

    ,"harbourmaster.widget.profile.error.email.fakeemail": Drupal.t('harbourmaster.widget.profile.error.email.fakeemail')

    ,"harbourmaster.widget.profile.error.email.required": Drupal.t('harbourmaster.widget.profile.error.email.required')

    ,"harbourmaster.widget.profile.error.email.LoginAlreadyExists": Drupal.t('harbourmaster.widget.profile.error.email.LoginAlreadyExists')

    ,"harbourmaster.widget.profile.error.email.EmailAlreadyExists": Drupal.t('harbourmaster.widget.profile.error.email.EmailAlreadyExists')

    ,"harbourmaster.widget.profile.error.email.ArgLoginIsRequired": Drupal.t('harbourmaster.widget.profile.error.email.ArgLoginIsRequired')

    ,"harbourmaster.widget.profile.error.email.LoginCannotBeEmpty": Drupal.t('harbourmaster.widget.profile.error.email.LoginCannotBeEmpty')

    ,"harbourmaster.widget.profile.error.oldPassword.string": Drupal.t('harbourmaster.widget.profile.error.oldPassword.string')

    ,"harbourmaster.widget.profile.error.oldPassword.required": Drupal.t('harbourmaster.widget.profile.error.oldPassword.required')

    ,"harbourmaster.widget.profile.error.oldPassword.IncorrectOldPassword": Drupal.t('harbourmaster.widget.profile.error.oldPassword.IncorrectOldPassword')

    ,"harbourmaster.widget.profile.error.password.string": Drupal.t('harbourmaster.widget.profile.error.password.string')

    ,"harbourmaster.widget.profile.error.password.minLength": Drupal.t('harbourmaster.widget.profile.error.password.minLength')

    ,"harbourmaster.widget.profile.error.password.regex": Drupal.t('harbourmaster.widget.profile.error.password.regex')

    ,"harbourmaster.widget.profile.error.password.required": Drupal.t('harbourmaster.widget.profile.error.password.required')

    ,"harbourmaster.widget.profile.error.password.ArgPasswordIsInvalid": Drupal.t('harbourmaster.widget.profile.error.password.ArgPasswordIsInvalid')

    ,"harbourmaster.widget.profile.error.password.NoNumbers": Drupal.t('harbourmaster.widget.profile.error.password.NoNumbers')

    ,"harbourmaster.widget.profile.error.password.NoLowerCaseLetters": Drupal.t('harbourmaster.widget.profile.error.password.NoLowerCaseLetters')

    ,"harbourmaster.widget.profile.error.password.NoUpperCaseLetters": Drupal.t('harbourmaster.widget.profile.error.password.NoUpperCaseLetters')

    ,"harbourmaster.widget.profile.error.password.NotMinEigth": Drupal.t('harbourmaster.widget.profile.error.password.NotMinEigth')

    ,"harbourmaster.widget.profile.error.verifyPassword.string": Drupal.t('harbourmaster.widget.profile.error.verifyPassword.string')

    ,"harbourmaster.widget.profile.error.verifyPassword.passwordEqual": Drupal.t('harbourmaster.widget.profile.error.verifyPassword.passwordEqual')

    ,"harbourmaster.widget.profile.error.verifyPassword.required": Drupal.t('harbourmaster.widget.profile.error.verifyPassword.required')

    ,"harbourmaster.widget.profile.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('harbourmaster.widget.profile.error.verifyPassword.PasswordsDoNotMatch')

    ,"harbourmaster.widget.profile.error.salutation.string": Drupal.t('harbourmaster.widget.profile.error.salutation.string')

    ,"harbourmaster.widget.profile.error.salutation.in": Drupal.t('harbourmaster.widget.profile.error.salutation.in')

    ,"harbourmaster.widget.profile.error.salutation.required": Drupal.t('harbourmaster.widget.profile.error.salutation.required')

    ,"harbourmaster.widget.profile.error.firstname.string": Drupal.t('harbourmaster.widget.profile.error.firstname.string')

    ,"harbourmaster.widget.profile.error.firstname.required": Drupal.t('harbourmaster.widget.profile.error.firstname.required')

    ,"harbourmaster.widget.profile.error.lastname.string": Drupal.t('harbourmaster.widget.profile.error.lastname.string')

    ,"harbourmaster.widget.profile.error.lastname.required": Drupal.t('harbourmaster.widget.profile.error.lastname.required')

    ,"harbourmaster.widget.profile.error.securityQuestions.string": Drupal.t('harbourmaster.widget.profile.error.securityQuestions.string')

    ,"harbourmaster.widget.profile.error.securityQuestions.required": Drupal.t('harbourmaster.widget.profile.error.securityQuestions.required')

    ,"harbourmaster.widget.profile.info.password": Drupal.t('harbourmaster.widget.profile.info.password')

    ,"harbourmaster.widget.profile.flash.success": Drupal.t('harbourmaster.widget.profile.flash.success')

    ,"harbourmaster.widget.profile.flash.error": Drupal.t('harbourmaster.widget.profile.flash.error')

    ,"harbourmaster.widget.signup.title.signup": Drupal.t('harbourmaster.widget.signup.title.signup')

    ,"harbourmaster.widget.signup.title.error": Drupal.t('harbourmaster.widget.signup.title.error')

    ,"harbourmaster.widget.signup.title.success": Drupal.t('harbourmaster.widget.signup.title.success')

    ,"harbourmaster.widget.signup.label.displayName": Drupal.t('harbourmaster.widget.signup.label.displayName')

    ,"harbourmaster.widget.signup.label.email": Drupal.t('harbourmaster.widget.signup.label.email')

    ,"harbourmaster.widget.signup.label.password": Drupal.t('harbourmaster.widget.signup.label.password')

    ,"harbourmaster.widget.signup.label.name": Drupal.t('harbourmaster.widget.signup.label.name')

    ,"harbourmaster.widget.signup.label.error": Drupal.t('harbourmaster.widget.signup.label.error')

    ,"harbourmaster.widget.signup.label.firstname": Drupal.t('harbourmaster.widget.signup.label.firstname')

    ,"harbourmaster.widget.signup.label.lastname": Drupal.t('harbourmaster.widget.signup.label.lastname')

    ,"harbourmaster.widget.signup.placeholder.displayName": Drupal.t('harbourmaster.widget.signup.placeholder.displayName')

    ,"harbourmaster.widget.signup.placeholder.email": Drupal.t('harbourmaster.widget.signup.placeholder.email')

    ,"harbourmaster.widget.signup.placeholder.password": Drupal.t('harbourmaster.widget.signup.placeholder.password')

    ,"harbourmaster.widget.signup.placeholder.verifyPassword": Drupal.t('harbourmaster.widget.signup.placeholder.verifyPassword')

    ,"harbourmaster.widget.signup.placeholder.firstname": Drupal.t('harbourmaster.widget.signup.placeholder.firstname')

    ,"harbourmaster.widget.signup.placeholder.lastname": Drupal.t('harbourmaster.widget.signup.placeholder.lastname')

    ,"harbourmaster.widget.signup.select.salutation.none": Drupal.t('harbourmaster.widget.signup.select.salutation.none')

    ,"harbourmaster.widget.signup.select.salutation.mr": Drupal.t('harbourmaster.widget.signup.select.salutation.mr')

    ,"harbourmaster.widget.signup.select.salutation.mrs": Drupal.t('harbourmaster.widget.signup.select.salutation.mrs')

    ,"harbourmaster.widget.signup.checkbox.terms_and_policy": Drupal.t('harbourmaster.widget.signup.checkbox.terms_and_policy')

    ,"harbourmaster.widget.signup.checkbox.subscription.advertising": Drupal.t('harbourmaster.widget.signup.checkbox.subscription.advertising')

    ,"harbourmaster.widget.signup.checkbox.subscription.newsletter": Drupal.t('harbourmaster.widget.signup.checkbox.subscription.newsletter')

    ,"harbourmaster.widget.signup.button.signup": Drupal.t('harbourmaster.widget.signup.button.signup')

    ,"harbourmaster.widget.signup.button.signin": Drupal.t('harbourmaster.widget.signup.button.signin')

    ,"harbourmaster.widget.signup.error.displayName.string": Drupal.t('harbourmaster.widget.signup.error.displayName.string')

    ,"harbourmaster.widget.signup.error.displayName.maxLength": Drupal.t('harbourmaster.widget.signup.error.displayName.maxLength')

    ,"harbourmaster.widget.signup.error.displayName.minLength": Drupal.t('harbourmaster.widget.signup.error.displayName.minLength')

    ,"harbourmaster.widget.signup.error.displayName.required": Drupal.t('harbourmaster.widget.signup.error.displayName.required')

    ,"harbourmaster.widget.signup.error.displayName.LoginAlreadyExists": Drupal.t('harbourmaster.widget.signup.error.displayName.LoginAlreadyExists')

    ,"harbourmaster.widget.signup.error.displayName.DisplayNameAlreadyExists": Drupal.t('harbourmaster.widget.signup.error.displayName.DisplayNameAlreadyExists')

    ,"harbourmaster.widget.signup.error.displayName.DisplayNameIsRequired": Drupal.t('harbourmaster.widget.signup.error.displayName.DisplayNameIsRequired')

    ,"harbourmaster.widget.signup.error.displayName.regex": Drupal.t('harbourmaster.widget.signup.error.displayName.regex')

    ,"harbourmaster.widget.signup.error.email.string": Drupal.t('harbourmaster.widget.signup.error.email.string')

    ,"harbourmaster.widget.signup.error.email.regex": Drupal.t('harbourmaster.widget.signup.error.email.regex')

    ,"harbourmaster.widget.signup.error.email.fakeemail": Drupal.t('harbourmaster.widget.signup.error.email.fakeemail')

    ,"harbourmaster.widget.signup.error.email.required": Drupal.t('harbourmaster.widget.signup.error.email.required')

    ,"harbourmaster.widget.signup.error.email.LoginAlreadyExists": Drupal.t('harbourmaster.widget.signup.error.email.LoginAlreadyExists')

    ,"harbourmaster.widget.signup.error.email.EmailAlreadyExists": Drupal.t('harbourmaster.widget.signup.error.email.EmailAlreadyExists')

    ,"harbourmaster.widget.signup.error.email.ArgLoginIsRequired": Drupal.t('harbourmaster.widget.signup.error.email.ArgLoginIsRequired')

    ,"harbourmaster.widget.signup.error.email.LoginCannotBeEmpty": Drupal.t('harbourmaster.widget.signup.error.email.LoginCannotBeEmpty')

    ,"harbourmaster.widget.signup.error.password.string": Drupal.t('harbourmaster.widget.signup.error.password.string')

    ,"harbourmaster.widget.signup.error.password.minLength": Drupal.t('harbourmaster.widget.signup.error.password.minLength')

    ,"harbourmaster.widget.signup.error.password.regex": Drupal.t('harbourmaster.widget.signup.error.password.regex')

    ,"harbourmaster.widget.signup.error.password.required": Drupal.t('harbourmaster.widget.signup.error.password.required')

    ,"harbourmaster.widget.signup.error.password.ArgPasswordIsInvalid": Drupal.t('harbourmaster.widget.signup.error.password.ArgPasswordIsInvalid')

    ,"harbourmaster.widget.signup.error.password.NoNumbers": Drupal.t('harbourmaster.widget.signup.error.password.NoNumbers')

    ,"harbourmaster.widget.signup.error.password.NoLowerCaseLetters": Drupal.t('harbourmaster.widget.signup.error.password.NoLowerCaseLetters')

    ,"harbourmaster.widget.signup.error.password.NoUpperCaseLetters": Drupal.t('harbourmaster.widget.signup.error.password.NoUpperCaseLetters')

    ,"harbourmaster.widget.signup.error.password.NotMinEigth": Drupal.t('harbourmaster.widget.signup.error.password.NotMinEigth')

    ,"harbourmaster.widget.signup.error.verifyPassword.string": Drupal.t('harbourmaster.widget.signup.error.verifyPassword.string')

    ,"harbourmaster.widget.signup.error.verifyPassword.passwordEqual": Drupal.t('harbourmaster.widget.signup.error.verifyPassword.passwordEqual')

    ,"harbourmaster.widget.signup.error.verifyPassword.required": Drupal.t('harbourmaster.widget.signup.error.verifyPassword.required')

    ,"harbourmaster.widget.signup.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('harbourmaster.widget.signup.error.verifyPassword.PasswordsDoNotMatch')

    ,"harbourmaster.widget.signup.error.salutation.string": Drupal.t('harbourmaster.widget.signup.error.salutation.string')

    ,"harbourmaster.widget.signup.error.salutation.in": Drupal.t('harbourmaster.widget.signup.error.salutation.in')

    ,"harbourmaster.widget.signup.error.salutation.required": Drupal.t('harbourmaster.widget.signup.error.salutation.required')

    ,"harbourmaster.widget.signup.error.firstname.string": Drupal.t('harbourmaster.widget.signup.error.firstname.string')

    ,"harbourmaster.widget.signup.error.firstname.required": Drupal.t('harbourmaster.widget.signup.error.firstname.required')

    ,"harbourmaster.widget.signup.error.lastname.string": Drupal.t('harbourmaster.widget.signup.error.lastname.string')

    ,"harbourmaster.widget.signup.error.lastname.required": Drupal.t('harbourmaster.widget.signup.error.lastname.required')

    ,"harbourmaster.widget.signup.error.terms.boolean": Drupal.t('harbourmaster.widget.signup.error.terms.boolean')

    ,"harbourmaster.widget.signup.error.terms.truthy": Drupal.t('harbourmaster.widget.signup.error.terms.truthy')

    ,"harbourmaster.widget.signup.intro.teaser": Drupal.t('harbourmaster.widget.signup.intro.teaser')

    ,"harbourmaster.widget.signup.intro.text": Drupal.t('harbourmaster.widget.signup.intro.text')

    ,"harbourmaster.widget.signup.info.password": Drupal.t('harbourmaster.widget.signup.info.password')

    ,"harbourmaster.widget.signup.flash.error": Drupal.t('harbourmaster.widget.signup.flash.error')

    ,"harbourmaster.widget.signup.flash.success": Drupal.t('harbourmaster.widget.signup.flash.success')

    ,"harbourmaster.widget.signin.title.signin": Drupal.t('harbourmaster.widget.signin.title.signin')

    ,"harbourmaster.widget.signin.title.success": Drupal.t('harbourmaster.widget.signin.title.success')

    ,"harbourmaster.widget.signin.title.passwordreset": Drupal.t('harbourmaster.widget.signin.title.passwordreset')

    ,"harbourmaster.widget.signin.title.passwordresetinit": Drupal.t('harbourmaster.widget.signin.title.passwordresetinit')

    ,"harbourmaster.widget.signin.title.unconfirmed": Drupal.t('harbourmaster.widget.signin.title.unconfirmed')

    ,"harbourmaster.widget.signin.label.username": Drupal.t('harbourmaster.widget.signin.label.username')

    ,"harbourmaster.widget.signin.label.password": Drupal.t('harbourmaster.widget.signin.label.password')

    ,"harbourmaster.widget.signin.placeholder.username": Drupal.t('harbourmaster.widget.signin.placeholder.username')

    ,"harbourmaster.widget.signin.placeholder.password": Drupal.t('harbourmaster.widget.signin.placeholder.password')

    ,"harbourmaster.widget.signin.button.signin": Drupal.t('harbourmaster.widget.signin.button.signin')

    ,"harbourmaster.widget.signin.button.signup": Drupal.t('harbourmaster.widget.signin.button.signup')

    ,"harbourmaster.widget.signin.button.passwordreset": Drupal.t('harbourmaster.widget.signin.button.passwordreset')

    ,"harbourmaster.widget.signin.button.unconfirmed": Drupal.t('harbourmaster.widget.signin.button.unconfirmed')

    ,"harbourmaster.widget.signin.error.username.required": Drupal.t('harbourmaster.widget.signin.error.username.required')

    ,"harbourmaster.widget.signin.error.username.string": Drupal.t('harbourmaster.widget.signin.error.username.string')

    ,"harbourmaster.widget.signin.error.username.error": Drupal.t('harbourmaster.widget.signin.error.username.error')

    ,"harbourmaster.widget.signin.error.username.credentials": Drupal.t('harbourmaster.widget.signin.error.username.credentials')

    ,"harbourmaster.widget.signin.error.username.regex": Drupal.t('harbourmaster.widget.signin.error.username.regex')

    ,"harbourmaster.widget.signin.error.username.AccountHasNoEmailAddress": Drupal.t('harbourmaster.widget.signin.error.username.AccountHasNoEmailAddress')

    ,"harbourmaster.widget.signin.error.username.NoSuchUser": Drupal.t('harbourmaster.widget.signin.error.username.NoSuchUser')

    ,"harbourmaster.widget.signin.error.username.UserNotConfirmed": Drupal.t('harbourmaster.widget.signin.error.username.UserNotConfirmed')

    ,"harbourmaster.widget.signin.error.password.required": Drupal.t('harbourmaster.widget.signin.error.password.required')

    ,"harbourmaster.widget.signin.error.password.error": Drupal.t('harbourmaster.widget.signin.error.password.error')

    ,"harbourmaster.widget.signin.error.password.credentials": Drupal.t('harbourmaster.widget.signin.error.password.credentials')

    ,"harbourmaster.widget.signin.error.requesttoken.LoginIsRequired": Drupal.t('harbourmaster.widget.signin.error.requesttoken.LoginIsRequired')

    ,"harbourmaster.widget.signin.error.requesttoken.AccountHasNoEmailAddress": Drupal.t('harbourmaster.widget.signin.error.requesttoken.AccountHasNoEmailAddress')

    ,"harbourmaster.widget.signin.error.requesttoken.NoSuchUser": Drupal.t('harbourmaster.widget.signin.error.requesttoken.NoSuchUser')

    ,"harbourmaster.widget.signin.message.unconfirmed": Drupal.t('harbourmaster.widget.signin.message.unconfirmed')

    ,"harbourmaster.widget.signin.message.unconfirmedresend": Drupal.t('harbourmaster.widget.signin.message.unconfirmedresend')

    ,"harbourmaster.widget.signin.message.passwordreset.email_sent": Drupal.t('harbourmaster.widget.signin.message.passwordreset.email_sent')

    ,"harbourmaster.widget.signin.link.passwordreset": Drupal.t('harbourmaster.widget.signin.link.passwordreset')

    ,"harbourmaster.widget.signin.link.activate": Drupal.t('harbourmaster.widget.signin.link.activate')

    ,"harbourmaster.widget.signin.tooltip.twitter": Drupal.t('harbourmaster.widget.signin.tooltip.twitter')

    ,"harbourmaster.widget.signin.tooltip.facebook": Drupal.t('harbourmaster.widget.signin.tooltip.facebook')

    ,"harbourmaster.widget.signin.tooltip.google": Drupal.t('harbourmaster.widget.signin.tooltip.google')

    ,"harbourmaster.widget.signin.tooltip.linkedin": Drupal.t('harbourmaster.widget.signin.tooltip.linkedin')

    ,"harbourmaster.widget.signin.tooltip.github": Drupal.t('harbourmaster.widget.signin.tooltip.github')

    ,"harbourmaster.widget.signin.flash.passwordreset.error": Drupal.t('harbourmaster.widget.signin.flash.passwordreset.error')

    ,"harbourmaster.widget.signin.flash.error": Drupal.t('harbourmaster.widget.signin.flash.error')

    ,"harbourmaster.widget.signin.alternate_login_methods": Drupal.t('harbourmaster.widget.signin.alternate_login_methods')

    ,"harbourmaster.widget.password.title.invalidToken": Drupal.t('harbourmaster.widget.password.title.invalidToken')

    ,"harbourmaster.widget.password.title.reset": Drupal.t('harbourmaster.widget.password.title.reset')

    ,"harbourmaster.widget.password.title.securityQuestions": Drupal.t('harbourmaster.widget.password.title.securityQuestions')

    ,"harbourmaster.widget.password.title.success": Drupal.t('harbourmaster.widget.password.title.success')

    ,"harbourmaster.widget.password.label.password": Drupal.t('harbourmaster.widget.password.label.password')

    ,"harbourmaster.widget.password.placeholder.answer": Drupal.t('harbourmaster.widget.password.placeholder.answer')

    ,"harbourmaster.widget.password.placeholder.password": Drupal.t('harbourmaster.widget.password.placeholder.password')

    ,"harbourmaster.widget.password.placeholder.verifyPassword": Drupal.t('harbourmaster.widget.password.placeholder.verifyPassword')

    ,"harbourmaster.widget.password.button.changePassword": Drupal.t('harbourmaster.widget.password.button.changePassword')

    ,"harbourmaster.widget.password.error.password.string": Drupal.t('harbourmaster.widget.password.error.password.string')

    ,"harbourmaster.widget.password.error.password.minLength": Drupal.t('harbourmaster.widget.password.error.password.minLength')

    ,"harbourmaster.widget.password.error.password.regex": Drupal.t('harbourmaster.widget.password.error.password.regex')

    ,"harbourmaster.widget.password.error.password.required": Drupal.t('harbourmaster.widget.password.error.password.required')

    ,"harbourmaster.widget.password.error.password.ArgPasswordIsInvalid": Drupal.t('harbourmaster.widget.password.error.password.ArgPasswordIsInvalid')

    ,"harbourmaster.widget.password.error.password.NewPasswordRequired": Drupal.t('harbourmaster.widget.password.error.password.NewPasswordRequired')

    ,"harbourmaster.widget.password.error.password.NoNumbers": Drupal.t('harbourmaster.widget.password.error.password.NoNumbers')

    ,"harbourmaster.widget.password.error.password.NoLowerCaseLetters": Drupal.t('harbourmaster.widget.password.error.password.NoLowerCaseLetters')

    ,"harbourmaster.widget.password.error.password.NoUpperCaseLetters": Drupal.t('harbourmaster.widget.password.error.password.NoUpperCaseLetters')

    ,"harbourmaster.widget.password.error.password.NotMinEigth": Drupal.t('harbourmaster.widget.password.error.password.NotMinEigth')

    ,"harbourmaster.widget.password.error.verifyPassword.string": Drupal.t('harbourmaster.widget.password.error.verifyPassword.string')

    ,"harbourmaster.widget.password.error.verifyPassword.passwordEqual": Drupal.t('harbourmaster.widget.password.error.verifyPassword.passwordEqual')

    ,"harbourmaster.widget.password.error.verifyPassword.required": Drupal.t('harbourmaster.widget.password.error.verifyPassword.required')

    ,"harbourmaster.widget.password.error.verifyPassword.PasswordsDoNotMatch": Drupal.t('harbourmaster.widget.password.error.verifyPassword.PasswordsDoNotMatch')

    ,"harbourmaster.widget.password.error.answer.string": Drupal.t('harbourmaster.widget.password.error.answer.string')

    ,"harbourmaster.widget.password.error.answer.required": Drupal.t('harbourmaster.widget.password.error.answer.required')

    ,"harbourmaster.widget.password.error.answer.CannotParseSecurityQuestions": Drupal.t('harbourmaster.widget.password.error.answer.CannotParseSecurityQuestions')

    ,"harbourmaster.widget.password.error.answer.IncompleteSecurityQuestions": Drupal.t('harbourmaster.widget.password.error.answer.IncompleteSecurityQuestions')

    ,"harbourmaster.widget.password.error.answer.WrongSecurityQuestionsAnswer": Drupal.t('harbourmaster.widget.password.error.answer.WrongSecurityQuestionsAnswer')

    ,"harbourmaster.widget.password.error.token.PasswordRecoveryTokenRequired": Drupal.t('harbourmaster.widget.password.error.token.PasswordRecoveryTokenRequired')

    ,"harbourmaster.widget.password.flash.error": Drupal.t('harbourmaster.widget.password.flash.error')

    ,"harbourmaster.widget.password.intro.text": Drupal.t('harbourmaster.widget.password.intro.text')

    ,"harbourmaster.widget.general.button.save": Drupal.t('harbourmaster.widget.general.button.save')

    ,"harbourmaster.widget.general.button.close": Drupal.t('harbourmaster.widget.general.button.close')

    ,"harbourmaster.widget.general.button.back": Drupal.t('harbourmaster.widget.general.button.back')

    ,"harbourmaster.widget.general.forbidden": Drupal.t('harbourmaster.widget.general.forbidden')

    ,"harbourmaster.widget.general.error": Drupal.t('harbourmaster.widget.general.error')

};



for(var key in __usermanager_language) {
  var value = __usermanager_language[key]
  if(key != value) { // only overwrite  Ember.I18n.translations when drupal give a translation
    var ref;
    if (typeof Ember !== "undefined" && Ember !== null ? (ref = Ember.I18n) != null ? ref.translations : void 0 : void 0) {

      unprefixedKey = key.replace("harbourmaster.widget.","");
      setNewTranslation(unprefixedKey, Ember.I18n.translations, value);
    }
  }
}

