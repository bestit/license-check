# Contributing

## General

- Discuss your change with:
    - CTO
    - Head of Development
    - Teamlead Development
- Create a own branch or fork for your changes.
- Write unit tests for your code.
- Check that your code match to our coding guidelines.
- Create a pull request for your changes.

## Semantic Versioning

We use [semantic versioning](https://semver.org) in correlation with the style enforced by our ruleset and not directly
with our source code. This means, that there could be breaking change in our code, but as long as the style is not changed
heavily, then the breaking change will not be mirrored in our version number:

- Patch-Version (last number): backwards-compatible bugfixes in our ruleset
- Minor-Version (middle number): backwards-compatible features in our ruleset (just warnings, deletions or auto-fixable errors)
- Major-Version (first number): breaking change in our ruleset

**We optimize our versioning for the usage of the sniffer, not for the development!**