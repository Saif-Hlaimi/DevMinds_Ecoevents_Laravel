<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Print Post #{{ $post->id }}</title>
  <style>
    @page { margin: 24px; }
    body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 14px; }
    .header { border-bottom: 1px solid #ddd; margin-bottom: 16px; padding-bottom: 8px; }
    .title { font-size: 22px; margin: 0; }
    .meta { color: #666; font-size: 12px; }
    .content { font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
    .image { margin-top: 12px; }
    .image img { max-width: 100%; height: auto; }
    .footer { border-top: 1px solid #ddd; margin-top: 16px; padding-top: 8px; font-size: 12px; color:#555; }
    .actions { margin-bottom: 12px; }
    @media print { .actions { display: none; } }
  </style>
</head>
<body>
  <div class="actions">
    <button onclick="window.print()">Print</button>
  </div>
  <div class="header">
    <h1 class="title">EcoEvents - Group Post</h1>
    <div class="meta">
      Post #{{ $post->id }} · Group: {{ optional($post->group)->name ?? 'N/A' }} · Author: {{ optional($post->user)->name ?? 'Unknown' }} · Date: {{ optional($post->created_at)->format('Y-m-d H:i') }}
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
    Generated from EcoEvents ({{ url('/') }}) on {{ now()->format('Y-m-d H:i') }}
  </div>

  <script>
    // Auto-open print dialog when page loads
    window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 300); });
  </script>
</body>
</html>
