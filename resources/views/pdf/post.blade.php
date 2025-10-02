<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Post #{{ $post->id }}</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111; }
    .header { border-bottom: 1px solid #ddd; margin-bottom: 16px; padding-bottom: 8px; }
    .title { font-size: 22px; margin: 0; }
    .meta { color: #666; font-size: 12px; }
    .content { font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
    .image { margin-top: 12px; }
    .image img { max-width: 100%; height: auto; }
    .footer { border-top: 1px solid #ddd; margin-top: 16px; padding-top: 8px; font-size: 12px; color:#555; }
  </style>
</head>
<body>
  <div class="header">
    <h1 class="title">EcoEvents - Group Post</h1>
    <div class="meta">
      Post #{{ $post->id }} · Group: {{ $post->group->name }} · Author: {{ $post->user->name }} · Date: {{ $post->created_at->format('Y-m-d H:i') }}
    </div>
  </div>

  @if(!empty($post->content))
    <div class="content">{!! nl2br(e($post->content)) !!}</div>
  @endif

  @php $img = $post->image_src; @endphp
  @if ($img)
    <div class="image">
      <img src="{{ $img }}" alt="Post image">
    </div>
  @endif

  <div class="footer">
    Generated from EcoEvents ({{ url('/') }})
  </div>
</body>
</html>
