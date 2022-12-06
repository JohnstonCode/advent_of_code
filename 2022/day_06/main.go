package main

import (
	"fmt"
	"io/ioutil"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	s := make([]string, 1)

	for i, v := range string(content) {
		if len(s) == 5 {
			s = append(s[:1], s[1+1:]...)
		}

		s = append(s, string(v))

		var packet string
		for _, c := range s {
			if !strings.Contains(packet, string(c)) {
				packet += string(c)
			}
		}

		if len(packet) == 4 {
			fmt.Printf("Part 1: %v\n", i+1)
			break
		}
	}

	s = make([]string, 1)

	for i, v := range string(content) {
		if len(s) == 15 {
			s = append(s[:1], s[2:]...)
		}

		s = append(s, string(v))

		var packet string
		for _, c := range s {
			if !strings.Contains(packet, string(c)) {
				packet += string(c)
			}
		}

		if len(packet) == 14 {
			fmt.Printf("Part 2: %v\n", i+1)
			break
		}
	}
}
