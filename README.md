Author: Mark Johnman
Date Last Modified: 17/11/13

This repository contains a simple, multi-user notes application that allows
users to register and create, read, update and delete text notes of arbitrary
length. Users must be logged in to view and manipulate their notes and are not
allowed to access other users' notes. The application has been secured against
the following OWASP/CWE vulnerabilities:

- SQL Injection (CWE-77, CWE-89 and CWE-564)
- Broken Authentication and Session Management (CWE-287 and CWE-384)
- Cross Site Scripting (XSS) (CWE-79)
- Security Misconfiguration

The application is served from a virtual machine configured by Vagrant
and provisioned by Ansible. It runs on a LAMP stack and uses the
CodeIgniter PHP framework. 

To use/test the application, simple fork and/or clone this repository, cd
into the cloned folder and type 'vagrant up' for the initial set up to be
performed. Following this, open a web browser and visit
http://localhost:8080/. Remember, in order for 'vagrant up' to work properly,
you should have both Vagrant and Ansible 1.4 installed on your machine. 
