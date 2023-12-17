package main

import "testing"

func TestPart1(t *testing.T) {
	input := "1abc2\npqr3stu8vwx\na1b2c3d4e5f\ntreb7uchet"
	result := part1(input)
	if result != "142" {
		t.Errorf("Part1 reult was incorrect, got: %s, expected %s", result, "142")
	}
}

func TestPart2(t *testing.T) {
	input := "two1nine\neightwothree\nabcone2threexyz\nxtwone3four\n4nineeightseven2\nzoneight234\n7pqrstsixteen"
	result := part2(input)
	if result != "281" {
		t.Errorf("Part2 reult was incorrect, got: %s, expected %s", result, "281")
	}
}
