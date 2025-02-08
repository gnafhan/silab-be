public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('first_name'); // Add this line
        $table->string('last_name'); // Add this line if not already present
        // ...existing code...
        $table->timestamps();
    });
}
