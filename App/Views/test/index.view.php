<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
@php {
    $name = 'tai';
    $a = 1;
    $data = ['name' => 'tài', 'age' => 18];
}

@for ($index = 0; $index < 10; $index++) {
    <p>Item {{ $index }}</p>
}

Hi my name is {{ $name | uppercase: 'avc' }},
My local {{ $name | uppercase }}
@if ($name) {
    @if ($name == 'tai') {
        <div
                @click="hello()"
                @class(['class1', 'class2'])
        >
        Tài
        </div>
    } @elseif ($name == 'huy') {
        <div @click="hello()">huy</div>
    } @else {
        <div @click="hello()">none</div>
    }
} @elseif ($a == 2) {
    <div @click="hello()">a = 1</div>
} @else {
    <div @click="hello()">not name</div>
}

@forelse ($data as $item) {
    @break
    <div @click="hello()">{{ $item }}</div>
} @empty {
    <div @click="hello()">empty</div>
}

@switch($a) {
    @case(1) {
        <div>1</div>
        @break
    }
    @case(2) {
        <div>2</div>
        @break
    }
    @default {
        <div>0</div>
        @break
    }
}


<?php if ($name) { ?>
    <div @click="hello()">click9</div>
<?php } ?>

<style>
    .test {
        color: red;
    }
</style>
<script>
    function hello() {
        alert('Hello');
    }
</script>
</body>
</html>
