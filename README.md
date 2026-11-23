# LAMP Bulletin Board — Penetration Test Target

> A PHP/MySQL bulletin board I built from scratch, then attacked with a black-box and gray-box penetration test. This repository is the **pre-remediation ("before") version** — the code an attacker would have faced.

[![Report](https://img.shields.io/badge/Pentest%20Report-11%20findings-e11d48?style=flat-square)](https://jihun.me/pentest-report.html)
[![Stack](https://img.shields.io/badge/Stack-PHP%208%20·%20MariaDB%20·%20Apache%20·%20Docker-0d1117?style=flat-square)](#tech-stack)
[![Programme](https://img.shields.io/badge/KnockOn-Elite%20White%20Hacker%20Bootcamp-2dd4bf?style=flat-square)](#background)

> ⚠️ **Intentionally vulnerable.** Do not deploy this code anywhere reachable from the internet.

---

## Background

Built during the 3rd Elite White Hacker Bootcamp run by KnockOn (South Korea, Nov 2024 – Feb 2025), where I finished in the Top 7. The brief was to understand a LAMP stack end to end by building one, then switch sides and assess it as an attacker.

| Phase | Period | Output |
|---|---|---|
| Build | Nov – Dec 2024 | Bulletin board on AWS Lightsail (LAMP, PHP 8), later containerised with Docker Compose |
| Attack | Jan – Feb 2025 | Black-box test, then gray-box source review to verify each fix |
| Report | Feb 2025 | [Full penetration test report](https://jihun.me/pentest-report.html) with PoC, CVSS v3.1 score and remediation per finding |

## Features

- User registration and login with `password_hash` / `password_verify`
- Create, list, edit and delete posts
- Image attachments on posts
- Session-based access control

## Findings summary

| ID | Finding | Severity | CVSS |
|---|---|---|---|
| V-01 | Remote code execution via file upload | Critical | 9.8 |
| V-02 | Authentication bypass via SQL injection | Critical | 9.1 |
| V-03 | Hardcoded database credentials | Critical | 9.1 |
| V-04 | Stored cross-site scripting | High | |
| V-05 | Insecure direct object reference (IDOR) | High | |
| V-06 | Session fixation and missing timeout | High | |
| V-07 | Directory listing enabled | High | |
| V-08 | PHP configuration exposure | Medium | |
| V-09 | No rate limiting on login | Medium | |
| V-10 | Missing CSRF protection | Medium | |
| V-11 | Verbose error messages | Low | |

Methodology: OWASP Testing Guide v4, manual testing with Burp Suite Community and browser DevTools, in an isolated environment with no production data. Proof-of-concept steps and patched code for every finding are in the [report](https://jihun.me/pentest-report.html).

## Run locally

```bash
cp .env.example .env        # set your own local DB credentials
docker compose up --build
# app:      http://localhost:8082
# MariaDB:  localhost:3307
```

## Repository structure

```
├── login.php / register.php / logout.php   # authentication
├── create_posts.php / edit_post.php        # post CRUD + file upload
├── list_posts.php / delete_post.php
├── uploads/                                # user-uploaded images (sample data)
├── Dockerfile.php / Dockerfile.db
└── docker-compose.yml
```

## Tech stack

PHP 8 · Apache · MariaDB · Docker Compose · AWS Lightsail · Burp Suite

## What I took away

- Client-side and blacklist validation is not validation: the upload RCE came from a case-sensitive extension check
- One unparameterised query is enough to own the admin account; prepared statements everywhere is the only safe default
- Chained low-severity issues (directory listing + verbose errors) are what turn a single bug into full compromise

---

Jihun Baek · [jihun.me](https://jihun.me) · [LinkedIn](https://www.linkedin.com/in/jihun-baek-505544270)
