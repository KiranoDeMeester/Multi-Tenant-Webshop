@extends('errors.layout')

@section('title', __('Geen toegang'))

@section('code', '403')

@section('message', __('Toegang geweigerd'))

@section('description', __('U heeft geen toestemming om deze pagina te bekijken. Controleer of u bent ingelogd met de juiste inloggegevens.'))
