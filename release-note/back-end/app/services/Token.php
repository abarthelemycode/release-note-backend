<?php 
namespace App\Services;

use Zenstruck\JWT\Token;
use Zenstruck\JWT\TokenBuilder;
use Zenstruck\JWT\Signer\HMAC\HS256;
use Zenstruck\JWT\Validator\ExpiresAtValidator;
use Zenstruck\JWT\Validator\IssuerValidator;
use Zenstruck\JWT\Validator\HasClaimValidator;
use Zenstruck\JWT\Exception\MalformedToken;
use Zenstruck\JWT\Exception\UnverifiedToken;
use Zenstruck\JWT\Exception\Validation\ExpiredToken;
use Zenstruck\JWT\Exception\Validation\ClaimMismatch;
use Zenstruck\JWT\Exception\ValidationFailed;

class TokenService
{

    public function __construct()
    {
    
    }

    public function createAuthenticationToken($userModel, $secretkey){

        $token = (new TokenBuilder())
            ->issuer($userModel->username)
            ->subject('support/jwt')
            ->audience('finelab')
           // ->expiresAt(new \DateTime('+1 days'))
            ->expiresAt(new \DateTime('+7 days'))
            ->notBefore(new \DateTime('+7 days'))
            ->issuedAt() // can pass \DateTime object - uses current time by default
            ->id($userModel->id)
            ->create(); 

        // this->key is in container setting
        $token->sign(new HS256(), $secretkey);
        return $token;
    }

    public function checkAuthenticationToken($token, $secretkey)
    {       
        $decodedToken = '';
        $error = null;
        // token is not correctly formed
        try {
            $decodedToken = Token::fromString($token);
        } catch (MalformedToken $e) {
            return $error = "Token malformed";
        }
        // check token doesn't correspond with secret key 
        try {
            $decodedToken->verify(new HS256(), $secretkey);
        } catch (UnverifiedToken $e) {
            return $error = "Token incorrect";
        }
        // check if the token has expired
        try {
            $decodedToken->validate(new ExpiresAtValidator());
        } catch (ExpiredToken  $e) {
            return $error = "Token has expired";
        }
        // // token is invalid - in this case, the issuer does not match
        try {
            $decodedToken->validate(new IssuerValidator('admin'));      
        } catch (ValidationFailed $e){
            return $error = "Token is invalid";
        }

        return $error;
    }




    public function createVersionToken($versionFrom, $versionTo,  $secretkey){

        $token = (new TokenBuilder())
            ->issuer('public')
            ->subject('support/jwt')
            ->audience('finelab')
            ->expiresAt(new \DateTime('+10 minutes'))
            ->notBefore(new \DateTime('+10 minutes'))
            ->issuedAt()
            // set custom claims
            ->set('versionFrom', $versionFrom)
            ->set('versionTo', $versionTo)
            ->create(); 

        // this->key is in container setting
        $token->sign(new HS256(), $secretkey);
        return $token;
    }

    public function checkVersionToken($token, $secretkey)
    {       
        $decodedToken = '';
        $error = null;
        // token is not correctly formed
        try {
            $decodedToken = Token::fromString($token);
        } catch (MalformedToken $e) {
            return $error = "Token malformed";
        }
        // check token doesn't correspond with secret key 
        try {
            $decodedToken->verify(new HS256(), $secretkey);
        } catch (UnverifiedToken $e) {
            return $error = "Token incorrect";
        }
        // check if the token has expired
        try {
            $decodedToken->validate(new ExpiresAtValidator());
        } catch (ExpiredToken  $e) {
            return $error = "Token has expired";
        }
        // // token is invalid - in this case, the issuer does not match
        try {
            $decodedToken->validate(new HasClaimValidator('versionFrom'));
            $decodedToken->validate(new HasClaimValidator('versionTo'));
        } catch (ClaimMismatch $e){
            return $error = "Token is invalid";
        }

        return $error;
    }


    public function getVersionsByToken($token)
    {   
        $decodedToken = Token::fromString($token);
        $versions['versionFrom']    = $decodedToken->get('versionFrom');
        $versions['versionTo']      = $decodedToken->get('versionTo');
        return $versions;
    }



}