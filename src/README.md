# Symfony components

```
"doctrine/annotations",
"liip/imagine-bundle",
"sensio/framework-extra-bundle",
"symfony/filesystem",
"symfony/finder",
"symfony/orm-pack",
"symfony/process":,
"symfony/serializer-pack",
"symfony/validator""
```

# API routes

Show all documents

```
GET /documents

```

Show one document

```
GET /documents/{document}

```

Create new document

```
POST /documents

```

Delete document and its attachments

```
DELETE /documents/{document}

```

PDF attachment resource (One document can have one attachment)

```
GET /documents/{document}/attachment

```

List of images, parsed from pdf document

```
GET /documents/{document}/attachment/previews

```

Single image resource

```
GET /documents/{document}/attachment/previews/{preview}

```

Add PDF attachment to document resource

```
POST /documents/{document}/attachment

```

