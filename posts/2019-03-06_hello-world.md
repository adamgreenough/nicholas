---
title: Hello World!
tags: Personal, Code
excerpt: Is this thing on?
---

Hello world! Welcome to my new personal website and blog, marking a renewed push as a freelancer. As is tradition, I have decided all my old posts are horrendous and they have been purged.

In case you're interested, the current (at time of writing!) version of the website was hand-built in HTML, CSS and PHP offering incredibly fast dynamic content.

When looking for a lightweight blogging solution that I could easily manage over Git (so flat-file makes sense), integrate easily in to existing websites, offer certain feeds in other projects (good API, headless perhaps?) and be simple to write from and host almost anywhere with no build step (dynamic content) I found that my options were very slim!

So I built my own.

Blog posts are written in markdown with YAML front matter and stored flat-file. Some lightweight PHP functions grab files from a posts directory and parse them in to an array of PHP objects for easy manipulation when theming as well as JSON via API and RSS feeds.

Currently it is pretty basic but hoping that someone else will find this useful I have [open sourced](https://github.com/adamgreenough/nicholas) it. Meet [Nicholas](https://github.com/adamgreenough/nicholas), the nearly-headless blogging system. I'm sure I'll do a follow up post with more information on that soon!

All of the [source code](https://github.com/adamgreenough/adam) for this website can be seen (but not touched, &copy; and all that) on GitHub.