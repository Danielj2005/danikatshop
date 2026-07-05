<?php

/*----------  Datos del servidor  ----------*/
const SERVER="localhost";
const DB="danikat_db";
const USER="root";
const PASS="";

const PHONE = "584244189963";
const COMPANY = "Tecnosystem";

/*----------  Datos de la encriptacion (No modificar) ----------*/
const METHOD="AES-256-CBC";
const SECRET_KEY = 'CTS_PRO_2026_SECURITY_99';
const SECRET_IV='102791';

/*----------  Datos de la conexión PDO (No modificar) ----------*/
const DBA="mysql:host=".SERVER.";dbname=".DB;
$conn = new PDO(DBA, USER, PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
