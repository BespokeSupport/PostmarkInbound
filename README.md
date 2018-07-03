# PostmarkInbound

Parse Inbound mail from [Postmark](https://postmarkapp.com)

https://postmarkapp.com/developer/webhooks/inbound-webhook

## Setup
```
    "require": {
        "bespoke-support/postmark-inbound": "1.0"
    }
```

## Usage
```
$inbound = new \BespokeSupport\PostmarkInbound\PostmarkInbound(file_get_contents('php://input'));
```

## Access of parsed message
via method or property
```

$inbound->Subject;

$inbound->Subject();
$inbound->FromEmail();
$inbound->FromFull();
$inbound->FromName();
$inbound->Date();
$inbound->OriginalRecipient(); 
$inbound->ReplyTo();
$inbound->MailboxHash();
$inbound->Tag();
$inbound->MessageID();
$inbound->TextBody();
$inbound->HtmlBody();
$inbound->StrippedTextReply();

$inbound->HasAttachments();

foreach ($inbound->Attachments() as $attachment) {
    $attachment->Name;
    $attachment->ContentType;
    $attachment->ContentLength;
    $attachment->Download('/');
}

foreach ($inbound->Recipients() as $recipient) {
    $recipient->Name;
    $recipient->Email;
}

foreach ($inbound->UndisclosedRecipients() as $undisclosedRecipient) {
    $undisclosedRecipient->Name;
    $undisclosedRecipient->Email;
}

```

## Thanks + Inspiration

[jjaffeux/postmark-inbound-php](https://github.com/jjaffeux/postmark-inbound-php)

## Licence
MIT Licence
